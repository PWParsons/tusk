<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->uuid,
            'name' => $this->name,
            'description' => $this->description,
            'created_at' => $this->created_at->toJson(),
            'updated_at' => $this->updated_at->toJson(),
            'project' => ProjectResource::make($this->whenLoaded('project')),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
        ];
    }
}
