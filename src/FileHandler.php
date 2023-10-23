<?php declare(strict_types=1);

    namespace STDW\Config\File;

    use STDW\Contract\ConfigHandlerInterface;
    use STDW\Config\File\FileException\ItemNotFoundException;
    use STDW\Config\File\FileException\StorageNotFoundException;


    class FileHandler extends ConfigHandlerInterface
    {
        private static array $collection = [];


        public function __construct()
        { }


        public function get(string $item): mixed
        {
            $segments = explode('.', $item);
            $item = self::$collection;

            foreach ($segments as $segment) {
                $item = &$item[$segment];

                if ( ! isset($item)) {
                    throw new ItemNotFoundException("Config: '". implode('.', $segments) ."' do not exists.");
                }
            }

            return $item;
        }

        public function load(string $storage): void
        {
            if ( ! file_exists($storage)) {
                throw new StorageNotFoundException("Config: Storage '$storage' do not exists.");
            }

            $files = glob($storage. DIRECTORY_SEPARATOR .'*');

            foreach ($files as $file) {
                $filename = pathinfo($file, PATHINFO_FILENAME);
                self::$collection[$filename] = include $file;
            }
        }
    }