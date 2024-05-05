<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;
use App\Models\Dislikes;
use App\Models\Likes;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */


    public function store(StoreCommentRequest $request, int $product_id): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            DB::beginTransaction();

            $comment = Comment::create([
                'comment' => $validatedData['comment'],
                'creator_id' => Auth::id(),
                'product_id' => $product_id,
            ]);

            DB::commit();

            return response()->json(['message' => 'Comment added successfully', 'comment' => $comment], 201);
        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'errors' => $e->getMessage()
            ], 422);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['message' => 'Failed to add comment', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        // Check if the authenticated user owns the comment
        // return response()->json(['error' => $comment->creator_id], 403);

        if ($comment->creator_id !== auth()->id()) {
            return response()->json(['error' => 'You are not authorized to update this comment.'], 403);
        }

        // Validate the request data
        $validatedData = $request->validated();

        try {
            // Begin transaction
            DB::beginTransaction();

            // Update the comment content
            $comment->update($validatedData);

            // Commit transaction
            DB::commit();

            return response()->json(['message' => 'Comment updated successfully', 'comment' => $comment]);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->getMessage()
            ], 422);
        } catch (Exception $e) {
            // Rollback transaction on error
            DB::rollBack();
            return response()->json(['error' => 'Failed to update comment', 'message' => $e->getMessage()], 500);
        }
    }



    public function like(Request $request, Comment $comment)
    {

        try {
            $userId = auth()->id();

            // Check if the user is the creator of the comment
            if ($comment->creator_id == $userId) {
                return response()->json(['error' => 'You cannot like your own comment'], 403);
            }

            // Check if the user has already liked the comment
            $existingLike = Likes::where('user_id', $userId)
                ->where('comment_id', $comment->id)
                ->first();

            if ($existingLike) {

                // User already liked the comment, so unlike it
                $existingLike->delete();

                // Increment the likes count
                $comment->like_ -= 1;

                // Save the updated comment
                $comment->save();

                return response()->json(['message' => 'Comment unlike successfully'], 200);
            }

            // Create a new like
            $like = new Likes();
            $like->user_id = $userId;
            $like->comment_id = $comment->id;
            $like->save();

            // Increment the likes count
            $comment->like_ += 1;

            // Save the updated comment
            $comment->save();

            return response()->json(['message' => 'Comment liked successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to like comment', 'message' => $e->getMessage()], 500);
        }
    }

    public function dislike(Request $request, Comment $comment)
    {
        try {
            $userId = auth()->id();

            // Check if the user is the creator of the comment
            if ($comment->creator_id == $userId) {
                return response()->json(['error' => 'You cannot dislike your own comment'], 403);
            }

            // Check if the user has already liked the comment
            $existingDislike = Dislikes::where('user_id', $userId)
                ->where('comment_id', $comment->id)
                ->first();

            if ($existingDislike) {

                // User already disliked the comment, so remove the dislike 
                $existingDislike->delete();

                // Increment the likes count
                $comment->dislike -= 1;

                // Save the updated comment
                $comment->save();

                return response()->json(['message' => 'Dislike removed'], 200);
            }

            // Create a new like
            $dislike = new Dislikes();
            $dislike->user_id = $userId;
            $dislike->comment_id = $comment->id;
            $dislike->save();

            // Increment the likes count
            $comment->dislike += 1;

            // Save the updated comment
            $comment->save();

            return response()->json(['message' => 'Comment disliked successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to dislike comment', 'message' => $e->getMessage()], 500);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        // Ensure that the authenticated user is authorized to delete the comment
        if ($comment->creator_id != auth()->id() && auth()->user()->role == 'user') {
            return response()->json(['error' => 'You are not authorized to delete this comment.'], 403);
        }

        // Attempt to delete the comment
        try {
            $comment->delete();
            return response()->json(['message' => 'Comment deleted successfully']);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to delete comment', 'message' => $e->getMessage()], 500);
        }
    }
}
