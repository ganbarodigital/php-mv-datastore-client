<?php

/**
 * Copyright (c) 2016-present Ganbaro Digital Ltd
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the names of the copyright holders nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category  Libraries
 * @package   DatastoreClient\MysqlOperations
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2016-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://ganbarodigital.github.io/php-mv-data-access-orm
 */

namespace GanbaroDigital\DatastoreClient\V1\MysqlOperations\DDL;

use GanbaroDigital\DatastoreClient\V1\Exceptions\StorageDDLFailed;
use GanbaroDigital\DatastoreClient\V1\PdoOperations;
use PDO;

/**
 * create an auto-increment column in a table
 */
class CreateAutoIncrement
{
    public static function using(PDO $dbConn, $table, $column)
    {
        // is there an existing primary key that we need to drop first?
        $dropPrimary = HasPrimaryKey::using($dbConn, $table);
        $sql = <<<EOS
ALTER TABLE `{$table}`
ADD COLUMN `{$column}` INT(11) NOT NULL AUTO_INCREMENT,
EOS;

        if ($dropPrimary) {
            $sql .= "DROP PRIMARY KEY," . PHP_EOL;
        }

        $sql .= <<<EOS
ADD PRIMARY KEY (`{$column}`);
EOS;

        PdoOperations\ExecuteStatement::using($dbConn, $sql, [], StorageDDLFailed::class);
    }
}