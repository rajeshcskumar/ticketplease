<?php

namespace App\Policies\V1;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TicketPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function update(User $user, Ticket $ticket)
    {
        return $user->id === $ticket->user_id
                    ? Response::allow()
                    : Response::deny('You do not own this post.');
    }
}
