<?php

namespace App\Livewire\Chat;

use Livewire\Component;
use App\Models\Conversation;

class Chat extends Component
{
    public $query;
    public $selectedConversation;
   
   
    public function render()
    {   
        return view('livewire.chat.chat');
    }
    public function mount(){
        $this->selectedConversation=Conversation::findOrFail($this->query);
        // dd($this->selectedConversation);
     }
}
