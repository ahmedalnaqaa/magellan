<?php
namespace TaskBundle\Constant;


final class TaskStatuses
{
    const NEW_TASK = 1;
    const NEW_TASK_LABEL = 'New';

    const IN_PROGRESS = 2;
    const IN_PROGRESS_LABEL = 'In Progress';

    const DONE = 3;
    const DONE_LABEL = 'Done';

    /**
     * Get task status list.
     *
     * @param bool $reversed
     * @return array
     */
    public static function getChoices($reversed = false)
    {
        if ($reversed) {
            return array(
                self::NEW_TASK_LABEL => self::NEW_TASK,
                self::IN_PROGRESS_LABEL => self::IN_PROGRESS,
                self::DONE_LABEL => self::DONE,
            );
        }
        return array(
            self::NEW_TASK => self::NEW_TASK_LABEL,
            self::IN_PROGRESS => self::IN_PROGRESS_LABEL,
            self::DONE => self::DONE_LABEL,
        );
    }

    /**
     * @param $role
     * @return mixed|string
     */
    public static function getLabel($role)
    {
        $roles = self::getChoices();

        return isset($roles[$role]) ? $roles[$role] : 'unknown';
    }
}