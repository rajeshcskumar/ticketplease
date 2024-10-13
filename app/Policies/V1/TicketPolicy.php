<?php

namespace App\Policies\V1;

use App\Models\Ticket;
use App\Models\User;
use App\Permissions\V1\Abilities;
use App\Traits\ApiResponses;
use Illuminate\Auth\Access\Response;

class TicketPolicy
{
    use ApiResponses;
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function store(User $user)
    {
        $user->tokenCan(Abilities::CreateTicket) ||
            $user->tokenCan(Abilities::CreateOwnTicket);
    }

    public function delete(User $user, Ticket $ticket)
    {
        if($user->tokenCan(Abilities::DeleteTicket)) {
            return true;
        }else if($user->tokenCan(Abilities::DeleteOwnTicket)) {
            return $user->id === $ticket->user_id
                    ? Response::allow()
                    : abort($this->error('You are not authorize to update this resource.',401));
        }
    }

    public function replace(User $user, Ticket $ticket)
    {
        if($user->tokenCan(Abilities::ReplaceTicket)) {
            return true;
        }
        return false;
    }


    public function update(User $user, Ticket $ticket)
    {
        if($user->tokenCan(Abilities::UpdateTicket)) {
            return true;
        }else if($user->tokenCan(Abilities::UpdateOwnTicket)) {
            return $user->id === $ticket->user_id
                    ? Response::allow()
                    : abort($this->error('You are not authorize to update this resource.',401));
        }
    }
}
