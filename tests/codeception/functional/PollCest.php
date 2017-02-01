<?php

namespace polls\functional;

use polls\FunctionalTester;
use Yii;
use humhub\modules\breakingnews\models\EditForm;

class BreakingNewsCest
{

    public function _before()
    {
        $this->module = Yii::$app->getModule('breakingnews');
        Yii::$app->cache->flush();
        $this->module->settings->delete('title');
        $this->module->settings->delete('message');
        $this->module->settings->delete('active');
        $this->module->settings->delete('timestamp');
    }
    
    public function testNewsActivation(FunctionalTester $I)
    {
        $I->amUser();
        $I->wantToTest('if the news activation works as expected');
        $I->amGoingTo('save the news form without activation');
        
        $form = new EditForm();
        $form->title = 'MyTitle';
        $form->active = false;
        $form->reset = true;
        $form->message = 'Test Message';
        $form->save();
        
        $I->expect('not to see the breaking news');
        $I->dontSeeBreakingNews();
        
        $I->amGoingTo('actite the news form');
        $form->active = true;
        $form->save();
        $I->expectTo('see the breaking news');
        $I->seeBreakingNews();
        
        $I->amGoingTo('save the breaking news form again without activation');
        $form->active = false;
        $form->save();
        $I->expect('not to see the breaking news');
        $I->dontSeeBreakingNews();
    }

}
