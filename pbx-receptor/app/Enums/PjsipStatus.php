<?php

namespace App\Enums;

enum PjsipStatus: int
{
    case IDLE = 0;
    case INUSE = 1;
    case BUSY = 2;
    case UNAVAILABLE = 4;
    case RINGING = 8;
    case ONHOLD = 16;
    case UNKNOWN = -1;

    /**
     * Map numerical PJSIP or AST Extension status code to semantic string (ONLINE/OFFLINE).
     */
    public function getSemanticStatus(): string
    {
        return match($this) {
            self::IDLE, self::INUSE, self::BUSY, self::RINGING, self::ONHOLD => 'ONLINE',
            self::UNAVAILABLE, self::UNKNOWN => 'OFFLINE',
        };
    }

    /**
     * Factory method from raw Asterisk text (e.g. from PAMI response)
     */
    public static function fromText(?string $text): self
    {
        $text = strtoupper(trim($text ?? ''));
        return match($text) {
            'IDLE' => self::IDLE,
            'INUSE' => self::INUSE,
            'BUSY' => self::BUSY,
            'RINGING' => self::RINGING,
            'UNAVAILABLE' => self::UNAVAILABLE,
            'ONHOLD' => self::ONHOLD,
            default => self::UNKNOWN,
        };
    }

    /**
     * Factory method from int code
     */
    public static function fromCode(?int $code): self
    {
        return self::tryFrom((int)$code) ?? self::UNKNOWN;
    }
}
