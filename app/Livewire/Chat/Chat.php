<?php

namespace App\Livewire\Chat;

use Livewire\Component;
use App\Models\Conversation;

class Chat extends Component
{
    public $query;
    public $selectConversation;
   
   
    public function render()
    {   
        return view('livewire.chat.chat');
    }
    public function mount(){
        $this->selectConversation=Conversation::findOrFail($this->query);
        dd($this->selectConversation);
     }
}
