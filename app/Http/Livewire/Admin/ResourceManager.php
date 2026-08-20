<?php

namespace App\Http\Livewire\Admin;

use App\Models\AcademicSession;
use App\Models\GradeScale;
use App\Models\HeadTeacherComment;
use App\Models\RemarkScale;
use App\Models\Section;
use App\Models\SectionClass;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherComment;
use App\Models\Lga;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class ResourceManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $resource;
    public $recordId;
    public $form = [];
    public $search = '';
    public $showForm = false;
    public $recordType;

    private function definitions()
    {
        return [
            'calendar' => ['Calendar', 'manage-calendar', AcademicSession::class, ['name'=>'Session','status'=>'Status','start_at'=>'Starts','end_at'=>'Ends'], ['name'=>'required|max:50','status'=>'required|in:Active,Not Active','start_at'=>'nullable|date','end_at'=>'nullable|date|after_or_equal:form.start_at']],
            'teachers' => ['Teachers', 'manage-teachers', Teacher::class, ['user_name'=>'Name','user_email'=>'Email','password'=>'Password','password_confirmation'=>'Confirm password','lga_id'=>'LGA','phone'=>'Phone','address'=>'Address','date_of_birth'=>'Date of birth'], []],
            'sections' => ['Sections', 'manage-sections', Section::class, ['name'=>'Name','level'=>'Level','class_tag'=>'Class tag','duration'=>'Duration'], ['name'=>'required|max:100','level'=>'required|integer|min:1','class_tag'=>'required|max:20','duration'=>'required|integer|min:1|max:10']],
            'subjects' => ['Subjects', 'manage-subjects', Subject::class, ['name'=>'Name'], ['name'=>'required|max:100']],
            'classes' => ['Classes', 'manage-classes', SectionClass::class, ['name'=>'Name','section_id'=>'Section','year_sequence'=>'Year','code'=>'Code','capacity'=>'Capacity','pass_mark'=>'Pass mark'], ['name'=>'required|max:100','section_id'=>'required|exists:sections,id','year_sequence'=>'required|max:30','code'=>'required|max:30','capacity'=>'nullable|integer|min:1','pass_mark'=>'nullable|integer|min:0|max:100']],
            'grading-scales' => ['Grading Scales', 'manage-grading-scales', GradeScale::class, ['section_id'=>'Section','grade'=>'Grade','from'=>'From','to'=>'To'], ['section_id'=>'nullable|exists:sections,id','grade'=>'required|max:10','from'=>'required|integer|min:0|max:100','to'=>'required|integer|min:0|max:100']],
            'remark-scales' => ['Remark Scales', 'manage-remark-scales', RemarkScale::class, ['section_id'=>'Section','scale'=>'Scale','percent'=>'Percent','grade'=>'Grade','remark'=>'Remark'], ['section_id'=>'nullable|exists:sections,id','scale'=>'required|max:50','percent'=>'required|max:20','grade'=>'required|max:10','remark'=>'required|max:255']],
            'comments' => ['Comments', 'manage-comments', TeacherComment::class, ['name'=>'Comment','gender'=>'Gender','comment_type'=>'Type'], ['name'=>'required|max:500','gender'=>'required|in:1,2','comment_type'=>'required|in:teacher,head']],
        ];
    }

    public function mount($resource)
    {
        abort_unless(isset($this->definitions()[$resource]), 404);
        $this->resource = $resource;
        $this->resetForm();
    }

    public function boot()
    {
        if ($this->resource) {
            $definition = $this->definitions()[$this->resource] ?? null;
            abort_unless($definition && Auth::check() && Auth::user()->status === 'Active' && Auth::user()->hasPermission($definition[1]), 403);
        }
    }

    public function updatingSearch() { $this->resetPage(); }
    public function create() { $this->resetForm(); $this->showForm = true; }

    public function edit($id, $type = null)
    {
        $definition = $this->definitions()[$this->resource];
        $model = $this->resource === 'comments' && $type === 'head' ? HeadTeacherComment::findOrFail($id) : $definition[2]::findOrFail($id);
        $this->recordId = $id;
        $this->recordType = $type;
        if ($this->resource === 'teachers') {
            $this->form = ['user_name'=>$model->user->name,'user_email'=>$model->user->email,'password'=>null,'password_confirmation'=>null,'lga_id'=>$model->lga_id,'phone'=>$model->phone,'address'=>$model->address,'date_of_birth'=>$model->date_of_birth];
            $this->showForm = true; return;
        }
        foreach ($definition[3] as $field => $label) $this->form[$field] = $field === 'comment_type' ? ($type ?: 'teacher') : $model->{$field};
        $this->showForm = true;
    }

    public function save()
    {
        $definition = $this->definitions()[$this->resource];
        if ($this->resource === 'teachers') { $this->saveTeacher(); return; }
        $rules = []; foreach ($definition[4] as $field => $rule) $rules['form.'.$field] = $rule;
        $data = $this->validate($rules)['form'];
        if ($this->resource === 'calendar' && $data['status'] === 'Active') AcademicSession::where('id','!=',$this->recordId ?: 0)->update(['status'=>'Not Active']);
        $modelClass = $definition[2];
        if ($this->resource === 'comments') { $type = $this->recordId ? ($this->recordType ?: 'teacher') : $data['comment_type']; unset($data['comment_type']); $modelClass = $type === 'head' ? HeadTeacherComment::class : TeacherComment::class; }
        $modelClass::updateOrCreate(['id'=>$this->recordId], $data);
        $this->resetForm(); session()->flash('success', $definition[0].' saved successfully.');
    }

    public function delete($id, $type = null)
    {
        $definition = $this->definitions()[$this->resource];
        $class = $this->resource === 'comments' && $type === 'head' ? HeadTeacherComment::class : $definition[2];
        try {
            $model = $class::findOrFail($id);
            if ($this->resource === 'calendar' && $model->academicSessionTerms()->exists()) throw new QueryException('', [], new \Exception());
            if ($this->resource === 'sections' && $model->sectionClasses()->exists()) throw new QueryException('', [], new \Exception());
            if ($this->resource === 'subjects' && $model->sectionClassSubjects()->exists()) throw new QueryException('', [], new \Exception());
            if ($this->resource === 'classes' && ($model->sectionClassStudents()->exists() || $model->sectionClassSubjects()->exists())) throw new QueryException('', [], new \Exception());
            if ($this->resource === 'teachers' && ($model->sectionClassTeachers()->exists() || $model->sectionClassSubjectTeachers()->exists())) throw new QueryException('', [], new \Exception());
            if ($this->resource === 'teachers') {
                DB::transaction(function () use ($model) {
                    $user = $model->user;
                    $model->delete();
                    if ($user) { $user->accessRoles()->detach(); $user->directPermissions()->detach(); $user->delete(); }
                });
            } else {
                $model->delete();
            }
            session()->flash('success', 'Record deleted.');
        }
        catch (\Throwable $e) { session()->flash('error', 'This record is in use and cannot be deleted.'); }
    }

    public function resetForm()
    {
        $this->recordId = null; $this->recordType = null; $this->showForm = false; $this->form = [];
        if ($this->resource && isset($this->definitions()[$this->resource])) foreach ($this->definitions()[$this->resource][3] as $field=>$label) $this->form[$field] = $field === 'comment_type' ? 'teacher' : null;
        if ($this->resource === 'calendar') $this->form['status'] = 'Not Active';
        $this->resetValidation();
    }

    private function saveTeacher()
    {
        $teacher = $this->recordId ? Teacher::with('user')->findOrFail($this->recordId) : null;
        $userId = $teacher ? $teacher->user_id : null;
        $rules = [
            'form.user_name'=>'required|string|max:255',
            'form.user_email'=>['required','email','max:255',Rule::unique('users','email')->ignore($userId)],
            'form.password'=>[$teacher?'nullable':'required','string','min:8','confirmed'],
            'form.lga_id'=>'required|exists:lgas,id','form.phone'=>'required|max:30','form.address'=>'required|max:255','form.date_of_birth'=>'required|date',
        ];
        $data = $this->validate($rules)['form'];
        DB::transaction(function () use ($teacher, $data) {
            $user = $teacher ? $teacher->user : new User();
            $user->fill(['name'=>$data['user_name'],'email'=>$data['user_email'],'role'=>'teacher','status'=>'Active']);
            if (!empty($data['password'])) $user->password = Hash::make($data['password']);
            if (!$teacher) $user->email_verified_at = now();
            $user->save();
            $teacherData = ['user_id'=>$user->id,'lga_id'=>$data['lga_id'],'phone'=>$data['phone'],'address'=>$data['address'],'date_of_birth'=>$data['date_of_birth']];
            if ($teacher) $teacher->update($teacherData); else $teacher = Teacher::create($teacherData);
            if ($role = Role::where('slug','teacher')->first()) $user->accessRoles()->syncWithoutDetaching([$role->id]);
        });
        $this->resetForm(); session()->flash('success', 'Teacher saved successfully.');
    }

    public function render()
    {
        $definition = $this->definitions()[$this->resource]; $class = $definition[2];
        if ($this->resource === 'teachers') $query = Teacher::with(['user','lga.state'])->whereHas('user', fn($q)=>$q->where('name','like','%'.$this->search.'%'));
        else $query = $class::query()->when($this->search, fn($q)=>$q->where(array_key_exists('name',$definition[3])?'name':array_key_first($definition[3]),'like','%'.$this->search.'%'));
        $records = $query->latest('id')->paginate(15);
        $headComments = $this->resource === 'comments' ? HeadTeacherComment::when($this->search, fn($q)=>$q->where('name','like','%'.$this->search.'%'))->get() : collect();
        return view('livewire.admin.resource-manager', compact('definition','records','headComments') + ['sections'=>Section::orderBy('name')->get(),'lgas'=>Lga::with('state')->orderBy('name')->get()]);
    }
}
