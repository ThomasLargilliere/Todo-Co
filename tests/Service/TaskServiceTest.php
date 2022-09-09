<?php

namespace App\Tests\Service;

use App\Entity\Task;
use App\Entity\User;
use App\Service\TaskService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskServiceTest extends KernelTestCase
{
    private TaskService $taskService;

    protected function setUp(): void
    {
        $this->taskService = static::getContainer()->get(TaskService::class);
    }

    public function testDeleteTaskWhenAuthorIsTheUserConnected()
    {
        // GIVEN
        $user = new User();
        $task = new Task();
        $task->setAuthor($user);

        // WHEN
        $result = $this->taskService->deleteTask($task, $user);

        // THEN
        $this->assertTrue($result);
    }

    public function testDeleteTaskWhenTheUserConnectedIsNotAuthor()
    {
        // GIVEN
        $userConnected = new User();
        $task = new Task();
        $task->setAuthor(new User());

        // WHEN
        $result = $this->taskService->deleteTask($task, $userConnected);

        // THEN
        $this->assertFalse($result);
    }

    public function testDeleteTaskWhenTheUserConnectedIsNotAuthorButIsAdminAndAuthorIsAno()
    {
        // GIVEN
        $userConnected = new User('Test');
        $userConnected->setRoles(['ROLE_ADMIN']);

        $authorAno = (new User)->setUsername('anonyme');

        $task = new Task();
        $task->setAuthor($authorAno);

        // WHEN
        $result = $this->taskService->deleteTask($task, $userConnected);
        
        // THEN
        $this->assertTrue($result);
    }

    public function testDeleteTaskWhenTheUserConnectedIsNotAuthorButIsAdminAndAuthorIsNotAno()
    {
        // GIVEN
        $userConnected = new User('Test');
        $userConnected->setRoles(['ROLE_ADMIN']);

        $task = new Task();
        $task->setAuthor(new User());

        // WHEN
        $result = $this->taskService->deleteTask($task, $userConnected);
        
        // THEN
        $this->assertFalse($result);
    }

    public function testToggleTaskWhenTaskIsFinished()
    {
        // GIVEN
        $task = new Task();
        $task->setIsDone(true);

        // WHEN
        $result = $this->taskService->toggleTask($task);

        // THEN
        $this->assertFalse($result);
    }

    public function testToggleTaskWhenTaskIsNotFinished()
    {
        // GIVEN
        $task = new Task();

        // WHEN
        $result = $this->taskService->toggleTask($task);

        // THEN
        $this->assertTrue($result);
    }

    public function testEditTaskWhenUserConnectedIsNotAuthor()
    {
        // GIVEN
        $userConnected = new User();
        $task = (new Task)->setAuthor(new User);

        // WHEN
        $result = $this->taskService->editTask($task, $userConnected);

        // THEN
        $this->assertFalse($result);
    }

    public function testEditTaskWhenUserConnectedIsAuthor()
    {
        // GIVEN
        $userConnected = new User();
        $task = (new Task)->setAuthor($userConnected);

        // WHEN
        $result = $this->taskService->editTask($task, $userConnected);

        // THEN
        $this->assertTrue($result);
    }
}