# UTF BOM Fixer

Remove file UTF BOM.

The UTF-8 BOM is a sequence of bytes (EF BB BF) that allows the reader to identify a file as being encoded in UTF-8.

Normally, the BOM is used to signal the endianness of an encoding, but since endianness is irrelevant to UTF-8, the BOM is unnecessary.

According to the Unicode standard, the BOM for UTF-8 files is not recommended

## Installation

#### Install dependencies
```
composer install
```
## Run from the command line
```
php ./fixer fix dir_name_or_file_name [--extension=php]
```
