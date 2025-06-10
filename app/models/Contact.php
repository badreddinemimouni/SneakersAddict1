<?php

require_once __DIR__ . '/Model.php';

class Contact extends Model {
    
    public function saveMessage($nom, $email, $message) {
        $sql = "INSERT INTO messages_contact (nom, email, message, date_creation) VALUES (?, ?, ?, NOW())";
        return $this->query($sql, [$nom, $email, $message]);
    }
    
    public function getAllMessages() {
        $sql = "SELECT * FROM messages_contact ORDER BY date_creation DESC";
        return $this->fetchAll($sql);
    }
    
    public function getMessageById($id) {
        $sql = "SELECT * FROM messages_contact WHERE id = ?";
        return $this->fetch($sql, [$id]);
    }
    
    public function markAsRead($id) {
        $sql = "UPDATE messages_contact SET lu = 1 WHERE id = ?";
        return $this->query($sql, [$id]);
    }
    
    public function deleteMessage($id) {
        $sql = "DELETE FROM messages_contact WHERE id = ?";
        return $this->query($sql, [$id]);
    }
    
    public function getUnreadCount() {
        $sql = "SELECT COUNT(*) as count FROM messages_contact WHERE lu = 0";
        $result = $this->fetch($sql);
        return $result ? $result['count'] : 0;
    }
    
    public function sendEmail($to, $subject, $body) {
        $headers = [
            'From: SneakersAddict <noreply@sneakersaddict.com>',
            'Reply-To: contact@sneakersaddict.com',
            'Content-Type: text/html; charset=UTF-8',
            'MIME-Version: 1.0'
        ];
        
        $emailBody = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #333; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f9f9f9; }
                .footer { padding: 20px; text-align: center; font-size: 12px; color: #666; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>SneakersAddict</h2>
                </div>
                <div class='content'>
                    {$body}
                </div>
                <div class='footer'>
                    <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
                    <p>&copy; " . date('Y') . " SneakersAddict - Tous droits réservés</p>
                </div>
            </div>
        </body>
        </html>";
        
        return mail($to, $subject, $emailBody, implode("\r\n", $headers));
    }
    
    public function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    public function sanitizeInput($input) {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
} 