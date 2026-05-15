<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\AppExtensionRuntime;
use DateTime;
use DateTimeInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('relative_time', [$this, 'relativeTime'], ['is_safe' => ['html']]),
            new TwigFilter('highlight', [$this, 'highlight'], ['is_safe' => ['html']]),

        ];
    }

    // public function getFunctions(): array
    // {
    //     return [
    //         new TwigFunction('function_name', [AppExtensionRuntime::class, 'doSomething']),
    //     ];
    // }

    //  public function processRelativeTime(DateTimeInterface $date)
    // {
    //     // $Current = Date('d.m h:s');
    //     //  $date
    // }


        public function relativeTime(DateTimeInterface $date): string
    {
        $now = new \DateTime();
        $diff = $now->diff($date);

        if ($diff->d > 0) {
            return $diff->d . ' days ago';
        }

        if ($diff->h > 0) {
            return $diff->h . ' hours ago';
        }

        if ($diff->i > 0) {
            return $diff->i . ' minutes ago';
        }

        return 'just now';
    }


    // public function highlight(string $keyword): String
    // {
    //     $keywords= ['awesome', 'well','done', 'Great', 'Job', 'Nailed','it', 'ate'];
        
    //     if( in_array($keyword, $keywords)){
    //         return '<strong>' . $keyword . '</strong>' ;
    //     }else{
    //         return $keyword;
    //     }

       
    // }

    
 // I DO NOT TAKE CREDIT FOR THIS METHOD, ALMOST ALL OF IT WAS DONE BY CLAUDE, COMPARE WITH MINE ABOVE
        public function highlight(string $text): string
    {
        $keywords = ['awesome', 'well', 'done', 'great', 'job', 'nailed', 'it', 'ate', '!'];

        
        $pattern = '/\b(' . implode('|', array_map('preg_quote', $keywords)) . ')\b/i';

        return preg_replace_callback($pattern, function (array $matches): string {
            return '<strong>' . $matches[0] . '</strong>';
        }, $text);
    }
}
