<?php

namespace tests\codeception\unit\modules\breakingnews;

use Yii;
use tests\codeception\_support\HumHubDbTestCase;
use Codeception\Specify;
use humhub\modules\breakingnews\models\EditForm;

class BreakingNewsFormTest extends HumHubDbTestCase
{

    use Specify;
    
    protected function setUp()
    {
        parent::setUp();
        $this->module = Yii::$app->getModule('breakingnews');
        Yii::$app->cache->flush();
        $this->module->settings->delete('title');
        $this->module->settings->delete('message');
        $this->module->settings->delete('active');
        $this->module->settings->delete('timestamp');
    }
    
    /**
     * Tests accidental space in one email rule
     */
    public function testValidateWithEmptyTitle()
    {
       $form = new EditForm();
       $form->message = 'Test Message!';
       $form->active = true;
       $form->reset = true;
       $this->assertFalse($form->validate());
       $form->title = 'MyTitle';
       $this->assertTrue($form->validate());
    }
    
    /**
     * Tests accidental space in one email rule
     */
    public function testValidateWithEmptyMessage()
    {
       $form = new EditForm();
       $form->title = 'MyTitle';
       $form->active = true;
       $form->reset = true;
       $this->assertFalse($form->validate());
       $form->message = 'Test Message';
       $this->assertTrue($form->validate());
    }
    
    /**
     * Tests accidental space in one email rule
     */
    public function testSaveValues()
    {
       $form = new EditForm();
       $form->title = 'MyTitle';
       $form->active = true;
       $form->reset = true;
       $form->message = 'Test Message';
       $form->save();
       
       $this->assertEquals('MyTitle', $this->module->settings->get('title'));
       $this->assertEquals('Test Message', $this->module->settings->get('message'));
       $this->assertEquals(true, $this->module->settings->get('active'));
       $this->assertNotNull($this->module->settings->get('timestamp'));
    }
    
    /**
     * Tests accidental space in one email rule
     */
    public function testOverwriteNews()
    {
       //First news
       $form = new EditForm();
       $form->title = 'MyTitle';
       $form->active = true;
       $form->reset = true;
       $form->message = 'Test Message';
       $form->save();
       
       $timestamp = $this->module->settings->get('timestamp');
       
       sleep(1);
       
       $form2 = new EditForm();
       $form2->title = 'MyTitle2';
       $form2->active = true;
       $form2->reset = true;
       $form2->message = 'Test Message2';
       $form2->save();
       
       $module = $this->module;
       
       $this->assertEquals('MyTitle2', $module->settings->get('title'));
       $this->assertEquals('Test Message2', $module->settings->get('message'));
       $this->assertEquals(true, $module->settings->get('active'));
       $this->assertNotEquals($timestamp, $module->settings->get('timestamp'));
    }
    
    /**
     * Tests accidental space in one email rule
     */
    public function testNonResetSave()
    {
       //First news
       $form = new EditForm();
       $form->title = 'MyTitle';
       $form->active = true;
       $form->reset = true;
       $form->message = 'Test Message';
       $form->save();
       
       $timestamp = $this->module->settings->get('timestamp');
       
       sleep(1);
       
       $form2 = new EditForm();
       $form2->title = 'MyTitle2';
       $form2->active = true;
       $form2->reset = false;
       $form2->message = 'Test Message2';
       $form2->save();
       
       $this->assertEquals('MyTitle2', $this->module->settings->get('title'));
       $this->assertEquals('Test Message2', $this->module->settings->get('message'));
       $this->assertEquals(true, $this->module->settings->get('active'));
       $this->assertEquals($timestamp, $this->module->settings->get('timestamp'));
    }
}
