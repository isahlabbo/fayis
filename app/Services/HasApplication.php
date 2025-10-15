<?php
namespace App\Services;

use Illuminate\Support\Facades\Storage;
use App\Services\Upload\FileUpload;
use Auth;

trait HasApplication
{
    use FileUpload;

    public function registerThisApplication($data)
    {
        // register application
        $application = $this->createApplication($data);

        // add educational record
        $this->saveApplicationEducationRecord($application, $data['school']);
        
        // add applicant quranic knowledge
        $this->saveApplicantQuaranicKnowledge($application, $data);
        
        // add language proficiency
        $this->saveApplicantLanguageProficiency($application, $data['language']);
        
        // add medical record
        $this->saveApplicantMedicalRecords($application, $data['diseases']);

        // add application declaration
        $this->saveApplicationDeclaration($application, $data);

        // update token status to used
        $this->updateApplicationToken($application);
    }

    public function saveApplicationDeclaration($application, $data)
    {
        $this->storeFile($application, 'declaration_sign', $data['signature'], 'Applications/'.$application->id.'/documents/declartion/');
        $application->update(['declared_at'=>$data['date']]);
    }

    public function updateApplicationToken($application)
    {
        $application->token->update(['status'=>'used']);
    }
    public function saveApplicantMedicalRecords($application, $data)
    {
        foreach ($data as $key => $value) {
            $application->medicalRecords()->firstOrCreate(['disease_id'=> $key]);
        }
    }
    public function saveApplicantLanguageProficiency($application, $data)
    {
        foreach($this->getApplicantLanguageProficiencyData($data) as $languageProficiency){
            
            $proficiency = $application->languageProfineciencies()->create([
                'language_id'=> $languageProficiency['language_id']
            ]);
            
            foreach($languageProficiency['proficiencies'] as $proficientIn){
                $proficiency->update([$proficientIn=>true]);
            }
        }
    }

    public function getApplicantLanguageProficiencyData($data)
    {
        $arabic = [];
        $english = [];
        $hausa = [];
        foreach($data as $key => $value){
            $proficiency = substr($key,1);
            switch (substr($key,0,1)) {
                case '1':
                    if($proficiency == 'reading'){
                        $arabic[] = 'reading';
                    }elseif($proficiency == 'writing'){
                        $arabic[] = 'writing';
                    }else{
                        $arabic[] = 'spoken';
                    }
                    break;
                
                case '2':
                    if($proficiency == 'reading'){
                        $english[] = 'reading';
                    }elseif($proficiency == 'writing'){
                        $english[] = 'writing';
                    }else{
                        $english[] = 'spoken';
                    }
                    break;

                default:
                    if($proficiency == 'reading'){
                        $hausa[] = 'reading';
                    }elseif($proficiency == 'writing'){
                        $hausa[] = 'writing';
                    }else{
                        $hausa[] = 'spoken';
                    }
                    break;
            }
        }
        return [
            ['language_id' => 1, 'proficiencies' => $arabic],
            ['language_id' => 2, 'proficiencies' => $english],
            ['language_id' => 3, 'proficiencies' => $hausa],
        ];
    }

    public function saveApplicantQuaranicKnowledge($application, $data)
    {
        
        $quranKnowlenge = $application->quranKnowledges()->create([
            'level_of_recitation' => $data['level_of_recitation'],
            'number_of_hizbs' => $data['number_of_hizbs'],
            'level_of_memorization' => $data['level_of_memorization'],
        ]);

        if($data['participation'] == 'yes'){
            foreach($this->getApplicantRecitationParticipationData($data['level']) as $participation){
                
                $quranKnowlenge->recitationParticipations()->create([
                    'recitation_participation_level_id'=> $participation['recitation_participation_level_id'],
                    'year'=> $participation['year'],
                    'position'=> $participation['position'],
                ]);
            }
        }
    }

    public function getApplicantRecitationParticipationData($data)
    {
        $participations = [];
        foreach($data as $key => $value){
            if($value){
                $participationLevelId = substr($key, 0, 1);
                $participations[] =[
                    'recitation_participation_level_id'=> $participationLevelId,
                    'year'=> $data[$participationLevelId.'_year'],
                    'position'=> $data[$participationLevelId.'_position'],
                ];
            }
        }

        return $participations;
    }
    
    public function createApplication($data)
    {
       $application = Auth::user()->guardian->applications()->create([
            'name'=>$data['name'],
            'date_of_birth'=>$data['date_of_birth'],
            'place_of_birth'=>$data['place_of_birth'],
            'lga_id'=>$data['lga'],
            'token_id'=>request('tokenId')
        ]);
        $application->update(['academic_session_id'=>$application->currentSession()->id]);
        $this->storeFile($application, 'picture', $data['picture'], 'Applications/'.$application->id.'/documents/');
        
        if($data['confirm_allergic'] == 'yes'){
            $application->update(['food_drug_allergic'=> $data['food_drug_allergic']]);
        }
        return $application;
    }

    public function saveApplicationEducationRecord($application, $data)
    {
        foreach($this->getApplicantCertificationData($data) as $certification){
            $educationalRecord = $application->educationalRecords()->create([
                'certification_id' => $certification['certification_id'],
                'school_name' => $certification['school_name'],
                'from' => $certification['from'],
                'to' => $certification['to'],
                ]);
            $this->storeFile($educationalRecord, 'certificate', $certification['certification'], 'Applications/'.$application->id.'/documents/Certifications/');    
        }
    }

    public function getApplicantCertificationData($data)
    {
        $certifications = [];
        foreach($data as $key=>$value){
            if($value){
                $iterationId = substr($key, 0, 1);
                $certificationId = substr($key, 1, 1);

                $certifications[] = [
                    'school_name' => $data[$iterationId.$certificationId.'_name'],
                    'from' => $data[$iterationId.$certificationId.'_from'],
                    'to' => $data[$iterationId.$certificationId.'_to'],
                    'certification' => $data[$iterationId.$certificationId.'_certificate'],
                    'certification_id'=>$certificationId
                ];
            }
        }
        return $certifications;
    }
}