<?php

namespace App\Libraries;

use App\Models\PasswordHistoryModel;

class PasswordPolicy
{
    protected $passwordHistoryModel;

    public function __construct()
    {
        $this->passwordHistoryModel = new PasswordHistoryModel();
    }

    /**
     * Get password policy requirements
     */
    public function getPolicy()
    {
        return [
            'min_length' => 8,
            'max_length' => 32,
            'require_uppercase' => true,
            'require_lowercase' => true,
            'require_numbers' => true,
            'require_special' => true,
            'prevent_reuse' => 5,
            'max_age_days' => 90,
            'min_age_days' => 1
        ];
    }

    /**
     * Validate password against policy
     */
    public function validatePassword($password, $userId = null)
    {
        $policy = $this->getPolicy();
        $errors = [];
        $requirements = [];
        $score = 0;

        // Check length
        if (strlen($password) < $policy['min_length']) {
            $errors[] = "Password minimal {$policy['min_length']} karakter";
        }
        if (strlen($password) > $policy['max_length']) {
            $errors[] = "Password maksimal {$policy['max_length']} karakter";
        }

        // Check uppercase
        if ($policy['require_uppercase'] && !preg_match('/[A-Z]/', $password)) {
            $errors[] = "Password harus mengandung huruf besar";
        } else {
            $score += 20;
        }

        // Check lowercase
        if ($policy['require_lowercase'] && !preg_match('/[a-z]/', $password)) {
            $errors[] = "Password harus mengandung huruf kecil";
        } else {
            $score += 20;
        }

        // Check numbers
        if ($policy['require_numbers'] && !preg_match('/[0-9]/', $password)) {
            $errors[] = "Password harus mengandung angka";
        } else {
            $score += 20;
        }

        // Check special characters
        if ($policy['require_special'] && !preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $password)) {
            $errors[] = "Password harus mengandung karakter khusus";
        } else {
            $score += 20;
        }

        // Check for common patterns
        if ($this->isCommonPassword($password)) {
            $errors[] = "Password terlalu umum, silakan gunakan password yang lebih kuat";
            $score = 0;
        }

        // Check for sequential characters
        if ($this->hasSequentialChars($password, 3)) {
            $errors[] = "Password mengandung karakter berurutan";
            $score -= 10;
        }

        // Check for repeated characters
        if ($this->hasRepeatedChars($password, 3)) {
            $errors[] = "Password mengandung karakter berulang";
            $score -= 10;
        }

        // Calculate strength
        $strength = 'very_weak';
        if ($score >= 20) $strength = 'weak';
        if ($score >= 40) $strength = 'fair';
        if ($score >= 60) $strength = 'good';
        if ($score >= 80) $strength = 'strong';
        if ($score == 100 && strlen($password) >= 12) $strength = 'very_strong';

        // Check password history if user ID provided
        if ($userId && $policy['prevent_reuse'] > 0) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            if ($this->passwordHistoryModel->wasPasswordUsed($userId, $hashedPassword, $policy['prevent_reuse'])) {
                $errors[] = "Password ini pernah digunakan sebelumnya, silakan gunakan password yang berbeda";
            }
        }

        $valid = empty($errors);

        // Build requirements met
        $requirements = [
            'length' => strlen($password) >= $policy['min_length'] && strlen($password) <= $policy['max_length'],
            'uppercase' => !$policy['require_uppercase'] || preg_match('/[A-Z]/', $password),
            'lowercase' => !$policy['require_lowercase'] || preg_match('/[a-z]/', $password),
            'numbers' => !$policy['require_numbers'] || preg_match('/[0-9]/', $password),
            'special' => !$policy['require_special'] || preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $password),
            'strength' => $strength
        ];

        return [
            'valid' => $valid,
            'score' => $score,
            'strength' => $strength,
            'errors' => $errors,
            'message' => $valid ? 'Password memenuhi persyaratan' : implode(', ', $errors),
            'requirements' => $requirements
        ];
    }

    /**
     * Check if password is common
     */
    private function isCommonPassword($password)
    {
        $commonPasswords = [
            'password', '123456', '12345678', '123456789', '12345',
            'qwerty', 'admin', 'welcome', 'password123', 'letmein',
            'monkey', 'dragon', 'football', 'baseball', 'superman'
        ];

        return in_array(strtolower($password), $commonPasswords);
    }

    /**
     * Check for sequential characters
     */
    private function hasSequentialChars($string, $length)
    {
        for ($i = 0; $i <= strlen($string) - $length; $i++) {
            $seq = substr($string, $i, $length);
            if (ctype_alpha($seq) || ctype_digit($seq)) {
                $ordValues = array_map('ord', str_split($seq));
                $differences = [];
                for ($j = 1; $j < count($ordValues); $j++) {
                    $differences[] = $ordValues[$j] - $ordValues[$j - 1];
                }
                if (count(array_unique($differences)) === 1 && abs($differences[0]) === 1) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Check for repeated characters
     */
    private function hasRepeatedChars($string, $length)
    {
        for ($i = 0; $i <= strlen($string) - $length; $i++) {
            $seq = substr($string, $i, $