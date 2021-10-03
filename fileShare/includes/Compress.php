<?php
    class CompressManager {
        public function compress($source, $target = '') {
            $fh = @fopen($source, 'rb');
            $gz = @gzopen($target, 'wb9');

            if (false === $fh || false === $gz) {
                return false;
            }

            while (!feof($fh)) {
                if (false === gzwrite($gz, fread($fh, 1024))) {
                    return false;
                }
            }

            fclose($fh);
            gzclose($gz);
            return true;
        }

        public function uncompress($source, $target) {
            $gz = @gzopen($source, 'rb');
            $fh = @fopen($target, 'wb');

            if (false === $fh || false === $gz) {
                return false;
            }

            while (!feof($gz)) {
                if (false === fwrite($fh, gzread($gz, 1024))) {
                    return false;
                }
            }

            fclose($fh);
            gzclose($gz);
            return true;
        }
    }
?>