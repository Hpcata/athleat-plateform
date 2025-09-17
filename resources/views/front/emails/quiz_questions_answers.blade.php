<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Questions and Answers</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            text-align: center;
        }
        .quiz-info {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .question-section {
            margin-bottom: 30px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
        }
        .section-header {
            background-color: #007bff;
            color: white;
            padding: 15px;
            font-weight: bold;
            text-transform: capitalize;
        }
        .question-item {
            padding: 20px;
            border-bottom: 1px solid #dee2e6;
        }
        .question-item:last-child {
            border-bottom: none;
        }
        .question-text {
            font-weight: bold;
            margin-bottom: 10px;
            color: #495057;
        }
        .answer-text {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            border-left: 4px solid #28a745;
        }
        .json-answer {
            background-color: #f1f3f4;
            padding: 15px;
            border-radius: 4px;
            overflow-x: auto;
            font-size: 12px;
            margin: 5px 0;
            font-family: 'Courier New', monospace;
            white-space: pre-wrap;
            word-wrap: break-word;
            border: 1px solid #ddd;
        }
        .decoded-answer {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            margin: 5px 0;
            border: 1px solid #ddd;
        }
        .answer-item {
            margin-bottom: 10px;
            padding: 8px;
            background-color: white;
            border-radius: 3px;
            border-left: 3px solid #28a745;
        }
        .answer-item:last-child {
            margin-bottom: 0;
        }
        .item-name {
            font-weight: bold;
            color: #495057;
            margin-bottom: 5px;
        }
        .item-details {
            font-size: 13px;
            color: #6c757d;
        }
        .step-info {
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
        }
        .footer {
            margin-top: 30px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            text-align: center;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Quiz Questions and Answers</h1>
        <p>Detailed breakdown of quiz responses</p>
    </div>

    <div class="quiz-info">
        <h3>Quiz Information</h3>
        <p><strong>Quiz ID:</strong> {{ $quiz->id }}</p>
        <p><strong>Started:</strong> {{ $quiz->started_at ? $quiz->started_at->format('F j, Y, g:i A') : 'N/A' }}</p>
        <p><strong>Completed:</strong> {{ $quiz->completed_at ? $quiz->completed_at->format('F j, Y, g:i A') : 'N/A' }}</p>
        @if($quiz->user_id)
            <p><strong>User ID:</strong> {{ $quiz->user_id }}</p>
        @endif
        @if($quiz->nutrition_score)
            <p><strong>Nutrition Score:</strong> {{ round(($quiz->nutrition_score / 35) * 100, 1) }}%</p>
        @endif
        @if($quiz->nutrition_feedback)
            <p><strong>Nutrition Feedback:</strong> {{ $quiz->nutrition_feedback }}</p>
        @endif
        @if($quiz->sports_score)
            <p><strong>Sports Score:</strong> {{ $quiz->sports_score }}</p>
        @endif
        @if($quiz->sports_feedback)
            <p><strong>Sports Feedback:</strong> {{ $quiz->sports_feedback }}</p>
        @endif
        @if($quiz->supplement_score)
            <p><strong>Supplements Score:</strong> {{ $quiz->supplement_score }}</p>
        @endif
        @if($quiz->supplement_feedback)
            <p><strong>Supplements Feedback:</strong> {{ $quiz->supplement_feedback }}</p>
        @endif
    </div>

    @foreach($questionsByForm as $formSlug => $questions)
        <div class="question-section">
            <div class="section-header">
                {{ ucfirst(str_replace('-', ' ', $formSlug)) }} Questions
            </div>

            @foreach($questions as $question)
                <div class="question-item">
                    <div class="question-text">
                        {{ $question->question }}
                    </div>
                    <div class="answer-text">
                        @php
                            $answer = $question->answer;
                            $answer = json_decode($answer, true);
                        @endphp
                        <strong>Answer:</strong>
                        @if(is_array($answer))
                            <div class="decoded-answer">
                                @if(isset($answer['option']))
                                    @if($answer['option'] !== null)
                                        {{ $answer['option'] }}
                                    @else
                                        <em>No option selected</em>
                                    @endif
                                @else
                                    @foreach($answer as $key => $value)
                                        <div class="answer-item">
                                            <div class="item-name">{{ ucfirst(str_replace(['-', '_'], ' ', $key)) }}</div>
                                            <div class="item-details">
                                                @if(is_array($value))
                                                    @if(isset($value['option']) && $value['option'] !== null)
                                                        {{ $value['option'] }}
                                                    @endif
                                                @else
                                                    {{ $value }}
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        @else
                            {{ $question->answer }}
                        @endif
                    </div>
                    <div class="step-info">
                        Step: {{ $question->step }} | Question Index: {{ $question->question_index }}
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach

    <div class="footer">
        <p>This email was automatically generated by the Athleat Platform</p>
        <p>Generated on: {{ now()->format('F j, Y, g:i A') }}</p>
    </div>
</body>
</html>
