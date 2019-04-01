<?php

/*
 * UserFrosting Support (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/support
 * @copyright Copyright (c) 2013-2019 Alexander Weissman
 * @license   https://github.com/userfrosting/support/blob/master/LICENSE.md (MIT License)
 */

use PHPUnit\Framework\TestCase;
use UserFrosting\Support\DotenvEditor\DotenvEditor;

class DotenvEditorTest extends TestCase
{
    protected $basePath;

    public function setUp()
    {
        $this->basePath = __DIR__.'/data/';
    }

    public function testBaseClass()
    {
        $editor = new DotenvEditor($this->basePath.'.env-backups/');
        $this->assertInstanceOf(DotenvEditor::class, $editor);

        // Test load
        $editor->load($this->basePath.'.env');
        $this->assertEquals('dbpass', $editor->getValue('DB_PASSWORD'));

        // Test backup
        $backups_before = $editor->getBackups();
        $editor->backup();
        $backups_after = $editor->getBackups();
        $this->assertEquals(1, count($backups_after) - count($backups_before));

        $editor->deleteBackups();
        $this->assertCount(0, $editor->getBackups());
    }

    /**
     * @expectedException \Jackiedo\DotenvEditor\Exceptions\FileNotFoundException
     */
    public function testBackupException()
    {
        new DotenvEditor($this->basePath.'backups/');
    }
}
