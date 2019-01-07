<?php

namespace Smartville\Domain\Issues\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Smartville\Domain\Company\Models\Company;
use Smartville\Domain\Issues\Models\Issue;

class NewCompanyIssuePosted extends Notification
{
    use Queueable;

    /**
     * @var Issue
     */
    public $issue;

    /**
     * @var Company
     */
    public $company;

    /**
     * @var $url
     */
    public $url;

    /**
     * @var $user
     */
    public $user;

    /**
     * Create a new notification instance.
     *
     * @param Issue $issue
     * @param Company $company
     */
    public function __construct(Issue $issue, Company $company)
    {
        $this->issue = $issue;
        $this->company = $company;
        $this->url = route('tenant.issues.show', $issue);
        $this->user = $issue->user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Issue Posted')
            ->markdown('tenant.emails.issues.new', [
                'issue' => $this->issue,
                'user' => $this->user,
                'company' => $this->company,
                'url' => $this->url,
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'url' => $this->url,
            'body' => "The issue is about: `{$this->issue->title}`",
            'title' => "{$this->user->name} posted an issue",
        ];
    }
}
