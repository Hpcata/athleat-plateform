<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Quiz;

class QuizQuestionsAnswersMail extends Mailable
{
    use Queueable, SerializesModels;

    public $quiz;
    public $questionsByForm;

    public function __construct(Quiz $quiz, array $questionsByForm)
    {
        $this->quiz = $quiz;
        $this->questionsByForm = $questionsByForm;
    }

    public function build()
    {
        return $this->subject('Quiz Questions and Answers - Quiz #' . $this->quiz->id)
                    ->view('front.emails.quiz_questions_answers')
                    ->with([
                        'quiz' => $this->quiz,
                        'questionsByForm' => $this->questionsByForm,
                    ]);
    }
}
