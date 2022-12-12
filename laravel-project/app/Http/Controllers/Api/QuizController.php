<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use App\Models\Quiz;
use App\Models\Rhyme;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QuizController extends Controller
{
    const ELOQUENT_SELECT_COLUMN = ["INDEX" => ['id', 'commentary'], "SHOW" => ['commentary']];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Todo: ログインしたユーザーを使用した処理にする
        // $user = Auth::user();
        $user = User::first();
        $quizzes = $user->quizzes()->get(self::ELOQUENT_SELECT_COLUMN["INDEX"]);
        return response()->json($quizzes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Todo: ログインしたユーザーを使用した処理にする
        // $user = Auth::user();
        $user = User::first();
        $inputQuiz = $request->quiz;

        $quiz = DB::transaction(static function () use ($inputQuiz, $user): Quiz {
            $quiz = $user->quizzes()->create(['commentary' => $inputQuiz['commentary']]);

            $inputChoices = $inputQuiz['choices'];

            foreach ($inputChoices as $inputChoice) {
                $inputRhyme = $inputChoice['rhyme'];

                $rhyme = Rhyme::firstOrNew(['content' => $inputRhyme]);
                if ($inputRhyme != "") {
                    $rhyme->save();
                }

                $quiz->choices()->create(['content' => $inputChoice['content'], 'rhyme_id' => $rhyme->id]);
            }
            return $quiz;
        });

        return response()->json($quiz, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $quiz = Quiz::select(self::ELOQUENT_SELECT_COLUMN["SHOW"])->find($id);

        return response()->json($quiz);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $quiz = Quiz::find($id);
        $quiz->update($request->all());
        return response()->json($quiz);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $quiz = Quiz::find($id);
        $quiz->delete();

        return response()->json($quiz);
    }
}
