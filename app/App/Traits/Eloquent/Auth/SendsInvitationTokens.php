<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 7/28/2018
 * Time: 9:52 PM
 */

namespace Smartville\App\Traits\Eloquent\Auth;

use Smartville\Domain\Users\Models\UserInvitation;

trait SendsInvitationTokens
{
    /**
     *  Generates a confirmation token for a user.
     * @param $email
     * @param $name
     * @param $type
     * @param array $data
     * @param null $expires
     * @return string
     */
    public function generateInvitationToken($email, $name, $type, $data = [], $expires = null)
    {
        $invitation = new UserInvitation;
        $invitation->fill([
            'name' => $name,
            'email' => $email,
            'type' => $type,
            'data' => $data,
            'token' => $token = str_random(config('settings.auth.invitation.token')),
            'code' => $token = str_random(config('settings.auth.invitation.code')),
            'expires_at' => $this->getInvitationTokenExpiry($expires),
        ]);
        $invitation->inviteable()->associate($this);
        $invitation->save();

        return $invitation;
    }

    /**
     * Get invitation token expiry date.
     *
     * @param null $expires
     * @return mixed
     */
    protected function getInvitationTokenExpiry($expires = null)
    {
        $expires = isset($expires) ? $expires : config('settings.auth.invitation.expiry');

        if (!$expires) {
            return null;
        }


        return $this->freshTimestamp()->addMinutes($expires);
    }

    /**
     * Get's the user's invitation token.
     *
     * @return mixed
     */
    public function invitationToken()
    {
        return $this->hasMany(UserInvitation::class);
    }
}