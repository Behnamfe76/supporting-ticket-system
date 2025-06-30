<html>
<head>
    <meta charset="UTF-8">
    <title>New Reply on Your Ticket</title>
    <style>
        body {
            background: #f4f6fb;
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 480px;
            margin: 40px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(44,62,80,0.08);
            padding: 32px 28px 24px 28px;
        }
        .header {
            text-align: center;
            margin-bottom: 24px;
        }
        .header h1 {
            color: #2d3748;
            font-size: 1.5rem;
            margin: 0 0 8px 0;
        }
        .header p {
            color: #718096;
            font-size: 1rem;
            margin: 0;
        }
        .ticket-info {
            background: #f7fafc;
            border-radius: 8px;
            padding: 16px;
            margin: 24px 0 16px 0;
        }
        .ticket-info strong {
            color: #4a5568;
        }
        .cta {
            text-align: center;
            margin: 32px 0 16px 0;
        }
        .cta a {
            background: #2563eb;
            color: #fff;
            text-decoration: none;
            padding: 12px 32px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 1rem;
            box-shadow: 0 2px 8px rgba(37,99,235,0.08);
            transition: background 0.2s;
            display: inline-block;
        }
        .cta a:hover {
            background: #1d4ed8;
        }
        .footer {
            text-align: center;
            color: #a0aec0;
            font-size: 0.95rem;
            margin-top: 32px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>You've Got a New Reply!</h1>
            <p>Hi {{ $user->name }},<br>
            There is a new reply on your support ticket.</p>
        </div>
        <div class="ticket-info">
            <p><strong>Ticket Subject:</strong> {{ $ticket->subject }}</p>
            <p><strong>Ticket Status:</strong> {{ ucfirst((string) $ticket->status->value) }}</p>
        </div>
        <div class="cta">
            <a href="{{ $url }}">View Reply</a>
        </div>
        <div class="footer">
            If you have any questions, just reply to this email or contact our support team.<br>
            &mdash; The Support Team
        </div>
    </div>
</body>
</html>
