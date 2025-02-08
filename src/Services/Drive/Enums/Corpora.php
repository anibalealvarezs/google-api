<?php

namespace Anibalealvarezs\GoogleApi\Services\Drive\Enums;

enum Corpora: string
{
    case user = 'user';
    case drive = 'drive';
    case domain = 'domain';
    case allDrives = 'allDrives';
}
