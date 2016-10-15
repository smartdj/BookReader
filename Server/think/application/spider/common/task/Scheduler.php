<?php
/**
 * Created by PhpStorm.
 * User: dj
 * Date: 2016/10/14
 * Time: 8:33
 */

namespace app\spider\common\task;


class Scheduler
{
    protected $maxTaskId = 0;
    protected $taskMap = []; // taskId => task
    protected $taskQueue;

    public function __construct() {
        $this->taskQueue = new SplQueue();
    }

    public function newTask(Generator $coroutine) {
        $tid = ++$this->maxTaskId;
        $task = new Task($tid, $coroutine);
        $this->taskMap[$tid] = $task;
        $this->schedule($task);
        return $tid;
    }

    public function schedule(Task $task) {
        $this->taskQueue->enqueue($task);
    }

//newTask()方法（使用下一个空闲的任务id）创建一个新任务,
//然后把这个任务放入任务map数组里.
// 接着它通过把任务放入任务队列里来实现对任务的调度.
// 接着run()方法扫描任务队列, 运行任务.如果一个任务结束了,
// 那么它将从队列里删除, 否则它将在队列的末尾再次被调度.
    public function run() {
        while (!$this->taskQueue->isEmpty()) {
            $task = $this->taskQueue->dequeue();
            $task->run();

            if ($task->isFinished()) {
                unset($this->taskMap[$task->getTaskId()]);
            } else {
                $this->schedule($task);
            }
        }
    }
}