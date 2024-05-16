<?php

namespace App\Livewire\Chat;

use App\Models\Message;
use Livewire\Component;
use App\Events\MessageSent;
use Livewire\Attributes\On;

class ChatBox extends Component
{
    public $selectedConversation;
    public $body = '';
    public $loadedMessages;
    public $sender_id;

    public function loadMessages()
    {
        $this->loadedMessages=Message::where('conversation_id',$this->selectedConversation->id)->get();

    }

    public function sendMessage()
    {

        $this->validate([
            'body'=>'required|string'
        ]);

        $createdMessage = Message::create([
            'conversation_id' => $this->selectedConversation->id,
            'sender_id' => auth()->id(),
            'receiver_id' => $this->selectedConversation->getReceiver()->id,
            'body' => $this->body,

        ]);

        
        broadcast(new MessageSent($createdMessage))->toOthers();

        $this->reset('body');

        // dd($createdMessage); //give array of all data

        // dd($this->body);

        $this->dispatch('scroll-bottom');

        $this->loadedMessages->push($createdMessage);

        $this->selectedConversation->updated_at=now();
        $this->selectedConversation->save();

        $this->dispatch('refresh')->to('chat.chat-list');

    }

    #[On('echo-private:chat-channel.{sender_id},MessageSent')]
    public function listenForMessage($event){
        // dd($event);

        
            if (isset($event['createdMessage']) && $event['createdMessage']['conversation_id'] == $this->selectedConversation->id) {
                $this->dispatch('scroll-bottom');
        
                $newMessage = Message::find($event['createdMessage']['id']);
        
                $this->loadedMessages->push($newMessage);
            }

        // $this->loadedMessages->push($createdMessage);
        // $createdMessage = Message::whereId($event['message']['id'])->first();
        // $this->chatMessage($chatMessage);
    
    }

    // #[On('echo:chat-channel,MessageSent')]
    // public function onPackageSent($event)
    // {
    //     dd($event);
    //     // $this->packageStatuses[] = $event;
    // }
    public function mount()
    {
        $this->sender_id = auth()->id();
        // dd($this->sender_id);
        $this->loadMessages();
    }

    public function render()
    {
        return view('livewire.chat.chat-box');
    }
}
