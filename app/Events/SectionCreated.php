<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Section;
use App\Models\State;

class SectionCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Section $section)
    {
        $this->registerAllSectionClasses($section);
    }
    public function registerAllSectionClasses($section)
    {
        foreach($section->requiredClasses() as $class){
            $class = $section->sectionClasses()->firstOrCreate([
                'name'=>strtoupper($class['name']),
                'year_sequence'=>$class['sequence'],
                'code'=>substr($class['name'],0,1),
                'section_class_group_id'=>$class['section_class_group_id']
            ]);
            event(new ClassRegistered($class));
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
