<?php

namespace TPaksu\TodoBar\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use TPaksu\TodoBar\Storage\DataStorageInterface;

class TodoBarTasks extends Controller
{
    protected $storage;

    public function __construct(DataStorageInterface $storage)
    {
        $this->storage = $storage;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($projectId)
    {
        if ($projectId >= 0) {
            $project = $this->storage->find($projectId) ?? null;
            if ($project) {
                return response()->json([
                    "status" => "success",
                    "tasks" => $project->tasks,
                ], 200);
            }
            return response()->json(["status" => "error", "error" => "Sorry, we can't find the project with that ID."]);
        }
        return response()->json([
            "status" => "success",
            "tasks" => null,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $projectId)
    {
        $request->validate([
            "content" => "required|string|filled",
        ]);

        $project = $this->storage->find($projectId);

        if ($project) {
            $project->tasks[] = (object) [
                "content" => $request->content,
                "completed" => 0,
            ];
            $this->storage->update($projectId, $project);
            return response()->json(["status" => "success"], 200);
        }
        return response()->json([
            "status" => "error",
            "error" => "We couldn't find the project that you wanted to add that task.",
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $projectId, $taskId)
    {
        $isStatusRequest = $request->has("status") == true;

        $request->validate(
            $request->has("status") ? ["status" => "required|boolean"] : ["content" => "required|string|filled"]
        );
        $update = $isStatusRequest ? "completed" : "content";
        $updateKey = $isStatusRequest ? "status" : "content";

        $project = $this->storage->find($projectId);

        if ($project) {
            if (isset($project->tasks[$taskId])) {
                $project->tasks[$taskId]->{$update} = $request->get($updateKey);

                $this->storage->update($projectId, $project);
                return response()->json(["status" => "success"], 200);
            }
            return response()->json([
                "status" => "error",
                "error" => "We couldn't find the task that you wanted to update.",
            ], 200);
        }
        return response()->json([
            "status" => "error",
            "error" => "We couldn't find the project that you wanted to add that task.",
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($projectId, $taskId)
    {
        $project = $this->storage->find($projectId);

        if ($project) {
            if (isset($project->tasks[$taskId])) {
                unset($project->tasks[$taskId]);
                $project->tasks = array_values($project->tasks);
                $this->storage->update($projectId, $project);
                return response()->json(["status" => "success"], 200);
            }
            return response()->json([
                "status" => "error",
                "error" => "We couldn't find the task that you wanted to update.",
            ], 200);
        }
        return response()->json([
            "status" => "error",
            "error" => "We couldn't find the project that you wanted to add that task.",
        ], 200);
    }
}
