<?php
namespace Lottery;

class View
{
    /**
     * If resource available by name, returns its content.
     *
     * @param string $name Page template name.
     * @return string|null
     */
    public static function page(string $name): ?string
    {
        if (preg_match('/^[a-z0-9_-]+$/', $name)) {

            $index = __DIR__ . '/../resources/views/index.html';
            $file = __DIR__ . '/../resources/views/components/' . $name . '.html';

            if (file_exists($index) && file_exists($file)) {

                $indexContent = file_get_contents($index);
                $fileContent = file_get_contents($file);

                return str_replace('{{body}}', $fileContent, $indexContent);
            }
        }

        return null;
    }
}
