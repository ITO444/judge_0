<?php

namespace App\Helpers;

use Genert\BBCode\BBCode;

class BB
{
    /**
     * Parses BBCode to HTML
     *
     * @param string $text
     * @return array
     */
    public static function convertToHtml(string $text)
    {
        /*$asd= [
            'hi' => [
                'pattern' => '/\[r\](.*?)\[\/r\]/s',
                'replace' => '<div class="text-right">$1</div>',
                'content' => '$1'
            ],];*/
        $bbCode = New BBCode(null, null);

        /*// Add "[link target=http://example.com]Example[/link]" parser.
        $bbCode->addParser(
            'custom-link',
            '/\[link target\=(.*?)\](.*?)\[\/link\]/s',
            '<a href="$1">$2</a>',
            '$1'
        );*/

        // Add "[link target=http://example.com]Example[/link]" parser.
        $bbCode->addParser(
            'section',
            '/\[section heading=(.*?)\](.*?)\[\/section\]/s',
            '<section class="card shadow"><h2 class="card-header">$1</h2><div class="card-body">$2</div></section>',
            '$1'
        )->addParser(
            'br',
            '/\[br\/?\]/s',
            '<br/>',
            '$1'
        )->addParser(
            'sample',
            '/\[sampletop left=(.*?) right=(.*?)\/?\]/s',
            '<div class="row font-weight-bold"><div class="col">$1</div><div class="col">$2</div></div>',
            '$1'
        )->addParser(
            'iorow',
            '/\[iorow\](.*?)\[\/iorow\]/s',
            '<div class="row">$1</div>',
            '$1'
        )->addParser(
            'io',
            '/\[io\](.*?)\[\/io\]/s',
            '<pre class="col border rounded shadow-sm m-1 py-2 io monospace">$1</pre>',
            '$1'
        )->addParser(
            'monospace',
            '/\[m\](.*?)\[\/m\]/s',
            '<span class="border rounded shadow-sm px-1 monospace">$1</span>',
            '$1'
        )->addParser(
            'center',
            '/\[center\](.*?)\[\/center\]/s',
            '<center>$1</center>',
            '$1'
        )->addParser(
            'table',
            '/\[table\](.*?)\[\/table\]/s',
            '<div class="table-responsive"><table class="table table-bordered">$1</table></div>',
            '$1'
        );

        return $bbCode->convertToHtml(htmlspecialchars($text));
    }
}