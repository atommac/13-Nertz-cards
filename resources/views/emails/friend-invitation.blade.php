<!DOCTYPE html>
<html>
<head>
    <title>Friend Invitation</title>
</head>
<body>
    <h2>Hello!</h2>
    <p>{{ $senderName }} has invited you to become friends and share players in the game.</p>
    <p>Click the link below to accept the invitation:</p>
    <a href="{{ $invitationLink }}" 
       style="display:inline-block; padding:10px 20px; color:white; background-color:blue; text-decoration:none; border-radius:5px;">
       Accept Invitation
    </a>
    <p>If you are not already registered, you will be prompted to register first.</p>
</body>
</html>