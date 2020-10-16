<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */

namespace humhub\modules\polls\models\filters;

use humhub\modules\stream\models\filters\StreamQueryFilter;
use Yii;


class PollsContentStreamFilter extends StreamQueryFilter
{

    /**
     * Default filters
     */
    const FILTER_POLLS_NOT_ANSWERED = 'filter_polls_notAnswered';

    /**
     * Array of stream filters to apply to the query.
     * There are the following filter available:
     *
     *  - 'filter_polls_notAnswered': Filters with only not answered questions
     *
     * @var array
     */
    public $filters = [];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['filters'], 'safe']
        ];
    }

    public function apply()
    {
        // Show only not answered questions by current logged in user
        if ($this->isFilterActive(self::FILTER_POLLS_NOT_ANSWERED)) {
            $this->filterPollsNotAnswered();
        }
    }

    public function isFilterActive($filter)
    {
        return in_array($filter, $this->filters);
    }

    protected function filterPollsNotAnswered()
    {
        $this->query->leftJoin('poll_answer_user', 'content.object_id=poll_answer_user.poll_id AND poll_answer_user.created_by=:userId', [':userId' => Yii::$app->user->id]);
        $this->query->andWhere(['is', 'poll_answer_user.id', new \yii\db\Expression('NULL')]);

        return $this;
    }
}
