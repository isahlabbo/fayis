<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\SectionClass;

class ClassRegistered
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    protected $class = null;

    public function __construct(SectionClass $class)
    {
        $this->class = $class;
        $this->addPTAFeeToThisClass();
    }

    public function addPTAFeeToThisClass()
    {
        foreach([1,2,3] as $termId){
            foreach([1,2] as $studentTypeId){
                $this->class->sectionClassPayments()->create([
                    'amount'=>1000,
                    'gender_id'=>3,
                    'term_id'=>$termId,
                    'student_type_id'=> $studentTypeId,
                    'name'=>'PTA Fee',
                ]);
            }
        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
