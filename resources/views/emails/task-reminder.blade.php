<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: Arial, sans-serif; background-color: #f9fafb; padding: 40px 0; margin: 0;">
    <div style="max-width: 500px; margin: 0 auto; background: white; border-radius: 8px; overflow: hidden;">
        @if($urgency === 'alert')
            <div style="background: #ef4444; padding: 20px; text-align: center;">
                <h1 style="color: white; font-size: 20px; margin: 0;">Task Due Today!</h1>
            </div>
        @else
            <div style="background: #f59e0b; padding: 20px; text-align: center;">
                <h1 style="color: white; font-size: 20px; margin: 0;">Task Due Tomorrow</h1>
            </div>
        @endif

        <div style="padding: 30px;">
            <h2 style="font-size: 18px; color: #111827; margin: 0 0 8px 0;">{{ $task->title }}</h2>

            @if($task->description)
                <p style="color: #6b7280; font-size: 14px; line-height: 1.6; margin: 0 0 20px 0;">{{ $task->description }}</p>
            @endif

            <div style="background: #f3f4f6; border-radius: 8px; padding: 15px; margin-bottom: 20px;">
                <table style="width: 100%; font-size: 14px;">
                    <tr>
                        <td style="color: #6b7280; padding: 4px 0;">Due date:</td>
                        <td style="color: #111827; font-weight: 600; padding: 4px 0;">{{ $task->due_date->format('M d, Y') }}</td>
                    </tr>
                    <tr>
                        <td style="color: #6b7280; padding: 4px 0;">Priority:</td>
                        <td style="padding: 4px 0;">
                            <span style="color: {{ $task->priority === 'high' ? '#ef4444' : ($task->priority === 'low' ? '#3b82f6' : '#f59e0b') }}; font-weight: 600;">
                                {{ ucfirst($task->priority) }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>

            @if($urgency === 'alert')
                <p style="color: #ef4444; font-size: 14px; font-weight: 600; margin: 0;">
                    This task is due today. Don't forget to complete it!
                </p>
            @else
                <p style="color: #6b7280; font-size: 14px; margin: 0;">
                    This is a friendly reminder that this task is due tomorrow.
                </p>
            @endif
        </div>

        <div style="background: #f9fafb; padding: 15px 30px; text-align: center; border-top: 1px solid #e5e7eb;">
            <p style="color: #9ca3af; font-size: 12px; margin: 0;">Sent from ToDo App</p>
        </div>
    </div>
</body>
</html>
