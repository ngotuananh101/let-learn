<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Course;
use App\Models\Learn;
use App\Models\LessonDetail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

use function PHPSTORM_META\type;

class LessonController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'choice' => 'nullable|string|in:import',
            ]);
            switch ($request->choice) {
                default:
                    $request->validate([
                        'name' => 'required|string|unique:lessons,name',
                        'description' => 'required|string|max:100',
                        'details' => 'required|array',
                    ]);
                    if ($request->has('password') && $request->password) {
                        $request->validate([
                            'password' => 'required|string|min:6',
                        ]);
                        $request->merge([
                            'password' => bcrypt($request->password),
                        ]);
                    }
                    $lesson = auth()->user()->lessons()->create($request->all());
                    $lesson->details()->createMany($request->details);

                    return response()->json([
                        'message' => 'Lesson created successfully',
                    ], 200);
                    break;
                case 'import':

                    return $this->import($request);
                    break;
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(Request $request, $id): JsonResponse
    {
        try {
            $lesson = Lesson::findOrFail($id)->load(['details']);
            //check if lesson status is active and lesson is not private
            if ($lesson->status != 'active' && $lesson->password != null)
                return response()->json([
                    'message' => 'Lesson is not allowed to be viewed',
                    'status' => 'error'
                ], 400);
            return response()->json([
                'lesson' => $lesson,
            ], 200);
            if ($request->query('type') == 'export') {
                //use export function
                return $this->export($request, $id);
            } else { // not type export, dont except other type
                return response()->json([
                    'message' => 'Type is valid',
                    'status' => 'error'
                ], 400);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $lesson = Lesson::findOrFail($id);
            // check lesson is deleted
            if ($lesson->status == 'inactive') {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 404,
                    'message' => 'Lesson not found!'
                ], 404);
            }
            // check user is owner of lesson
            if ($lesson->user_id != auth()->user()->id) {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 403,
                    'message' => 'You are not owner of this lesson!'
                ], 403);
            }
            // update lesson
            $lessonController = new \App\Http\Controllers\Admin\LessonController();
            return $lessonController->update($request, $id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
            $lesson = Lesson::findOrFail($id);
            //check auth user is owner of lesson
            // if lesson is inactive then delete

            if ($lesson->user_id != auth()->user()->id) {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 403,
                    'message' => 'You are not owner of this lesson!'
                ], 403);
            }
            if ($lesson->status == 'inactive') {
                //$lesson->delete();
                //cant delete lesson if lesson is inactive
                return response()->json([
                    'status' => 'error',
                    'status_code' => 400,
                    'message' => 'Lesson is already deleted!'
                ], 400);
            } else {
                // Soft delete lesson if lesson is active
                $lesson->update([
                    'status' => 'inactive'
                ]);
                return response()->json([
                    'status' => 'success',
                    'status_code' => 200,
                    'message' => 'Delete lesson successfully!',
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    //show all lesson by user id
    public function showAllLessonByUserId(): JsonResponse
    {
        try {
            $user = auth()->user();
            $lesson = $user->lesson()->where('status', 'active')->get();
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Get lesson successfully!',
                'data' => $lesson
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    // show progress by lesson id
    public function showProgressByLessonId($id): JsonResponse
    {
        try {
            $lesson = Lesson::findOrFail($id);
            //count the number of lesson detail in lesson
            $countLessonDetail = $lesson->lessonDetail()->count();

            //Find learn by learn id
            $learn = Learn::where('lesson_id', $id)->where('user_id', auth()->user()->id)->first();
            //Get learned from learn
            $countLearned = count(explode(',', $learn->learned));

            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Get progress successfully!',
                'data' => [
                    // 'countLessonDetail' => $countLessonDetail,
                    // 'learn' => $learn,
                    // 'countLerned' => $countLearned,
                    'progress' => ($countLearned / $countLessonDetail) * 100 . '%',
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function learnForImport(Request $request, $lesson_id)
    {
        try {
            $quantity = $request->input('quantity', 20); //request quantity of lesson details
            $reverse = $request->input('reverse', false); //request reverse term and definition
            $mixAnswers = $request->input('mix_answers', false); //request mix answer part
            $mixDetails = $request->input('mix_details', false); //request mix lesson details

            $lesson = Lesson::findOrFail($lesson_id);
            //get learned lesson details from learn table
            $learn = Learn::where('user_id', auth()->user()->id)->where('lesson_id', $lesson_id)->first();
            //get id of learned lesson details
            $learned = $learn ? explode(',', $learn->learned) : [];
            //get id of relearn lesson details
            $relearn = $learn ? explode(',', $learn->relearn) : [];
            // $lessonDetails = LessonDetail::where('lesson_id', $lesson_id)->whereNotIn('id', $learned)->whereNotIn('id', $relearn);
            $lessonDetails = [];
            //get relearn lesson details by quantity
            if ($relearn) {
                $lessonDetails = LessonDetail::whereIn('id', $relearn)->get();
            }
            if ($mixDetails) {
                //if learned and relearn lesson details are empty
                if (count($learned) === 0 && count($relearn) === 0) {
                    $lessonDetails = LessonDetail::where('lesson_id', $lesson_id)->inRandomOrder()->take($quantity)->get();
                } else {
                    $notLearn = LessonDetail::where('lesson_id', $lesson_id)->whereNotIn('id', $learned)->whereNotIn('id', $relearn)->inRandomOrder()->take($quantity - count($lessonDetails))->get();
                    $lessonDetails = $lessonDetails->merge($notLearn);
                }
            } else {
                //if learned and relearn lesson details are empty
                if (count($learned) === 0 && count($relearn) === 0) {
                    $lessonDetails = LessonDetail::where('lesson_id', $lesson_id)->take($quantity)->get();
                } else {
                    $notLearn = LessonDetail::where('lesson_id', $lesson_id)->whereNotIn('id', $learned)->whereNotIn('id', $relearn)->take($quantity - count($lessonDetails))->get();
                    $lessonDetails = $lessonDetails->merge($notLearn);
                }
            }
            //$lessonDetails = $lessonDetails->where('id', 51);
            $response = ['lesson_id' => $lesson_id, 'lesson_details' => []];
            foreach ($lessonDetails as $lessonDetail) {
                try {
                    $term = $lessonDetail->term;
                    $definition = $lessonDetail->definition;
                    if ($reverse) {
                        // check if the term and definition are not empty before swapping them
                        if (!empty($term) && !empty($definition)) {
                            $temp = $term;
                            $term = $definition;
                            $definition = $temp;
                        } else {
                            // if either term or definition is empty, set the response accordingly
                            return response()->json([
                                'status' => 'error',
                                'status_code' => 500,
                                'message' => 'One or both of the term and definition are empty.'
                            ], 500);
                        }
                    }
                    $answers = [];
                    // replace special characters with a space character
                    $term = preg_replace('/[\n\r\t]+/', ' ', $term);

                    // check if the term is a multiple choice question
                    if (preg_match('/^(.*?)\s*[A-Ma-m]\.\s*(.*)/is', $term, $matches)) {
                        $question = trim($matches[1]);
                        $options_str = $matches[2];
                        // split the options into arrays
                        $options = preg_split('/\s*[a-m]\.\s*/i', $options_str, -1, PREG_SPLIT_NO_EMPTY);
                        //check if $options might be missing any options like A, B, C, D

                        $answers = array_map('trim', $options);
                        if (count($answers) < 2) {
                            // if the number of options is less than 4, then the options are missing
                            return response()->json([
                                'status' => 'error',
                                'status_code' => 500,
                                'message' => 'Please recheck the options for the question "' . $question . '"' . ' in the lesson "' . $lesson->name . '" ,question_id: ' . $lessonDetail->id
                            ], 500);
                        }
                        //count characters of definition
                        $count = strlen($definition);
                        //if count > 1, then correct answer include more than 1 option
                        if ($count > 1) {
                            $correct_answer = [];
                            //loop through definition
                            for ($i = 0; $i < $count; $i++) {
                                //get character of definition
                                $char = substr($definition, $i, 1);
                                //get correct answer by character
                                switch (trim($char)) {
                                    case 'A':
                                    case 'a':
                                        $correct_answer[] = $answers[0];
                                        break;
                                    case 'B':
                                    case 'b':
                                        $correct_answer[] = $answers[1];
                                        break;
                                    case 'C':
                                    case 'c':
                                        $correct_answer[] = $answers[2];
                                        break;
                                    case 'D':
                                    case 'd':
                                        $correct_answer[] = $answers[3];
                                        break;
                                    case 'E':
                                    case 'e':
                                        $correct_answer[] = $answers[4];
                                        break;
                                    case 'F':
                                    case 'f':
                                        $correct_answer[] = $answers[5];
                                        break;
                                    case 'G':
                                    case 'g':
                                        $correct_answer[] = $answers[6];
                                        break;
                                    default:
                                        $correct_answer[] = '';
                                        break;
                                }
                            }
                            //convert correct answer to string
                            $correct_answer = trim(implode('|| ', $correct_answer));
                        }else {
                        switch (trim($definition)) {
                            case 'A':
                            case 'a':
                                $correct_answer = isset($answers[0]) ? $answers[0] : "Recheck this lesson detail";
                                break;
                            case 'B':
                            case 'b':
                                $correct_answer = isset($answers[1]) ? $answers[1] : (isset($answers[0]) ? $answers[0] : "Recheck this lesson detail");
                                break;
                            case 'C':
                            case 'c':
                                $correct_answer = isset($answers[2]) ? $answers[2] : (isset($answers[1]) ? $answers[1] : "Recheck this lesson detail");
                                break;
                            case 'D':
                            case 'd':
                                $correct_answer = isset($answers[3]) ? $answers[3] : (isset($answers[2]) ? $answers[2] : "Recheck this lesson detail");
                                break;
                            case 'E':
                            case 'e':
                                $correct_answer = $answers[4];
                                break;
                            case 'F':
                            case 'f':
                                $correct_answer = $answers[5];
                                break;
                            case 'G':
                            case 'g':
                                $correct_answer = $answers[6];
                                break;
                            default:
                                $correct_answer = '';
                                break;
                        }

                        $correct_answer = trim($correct_answer);
                    }
                        if ($mixAnswers) {
                            shuffle($answers);
                        }
                    }
                    // check if the term is a true/false question
                    else if (preg_match('/^(.*)\s*[a-z]\.\s*(True|False)/is', $term, $matches)) {
                        $question = trim($matches[1]);
                        $answers = array_map('trim', [$matches[2], $matches[3]]);

                        // determine the correct answer
                        $correct_answer = ($definition === 'True') ? 'True' : 'False';

                        if ($mixAnswers) {
                            shuffle($answers);
                        }
                    }
                    // if the term is neither multiple choice nor true/false
                    else {
                        $question = $term;
                        $correct_answer = trim($definition);

                        //get current $response and get the and $answers of other lessonDetail except true/false
                        $otherAnswers = $response['lesson_details'];
                        $otherAnswers = array_filter($otherAnswers, function ($item) {
                            return count($item['answers']) > 2;
                        });

                        //check if there are enough answers in other answers or not
                        if (count($otherAnswers) >= 3) {
                            //get random 3 answers from other otherAnswers
                            $otherAnswers = array_map(function ($item) {
                                return $item['answers'];
                            }, $otherAnswers);
                            $otherAnswers = array_merge(...$otherAnswers);
                            shuffle($otherAnswers);
                            $otherAnswers = array_slice($otherAnswers, 0, 3);

                            //add correct answer to otherAnswers
                            array_push($otherAnswers, $correct_answer);
                            // shuffle the answer options
                            shuffle($otherAnswers);
                            // lesson the answer options to the shuffled $otherAnswers
                            $answers = $otherAnswers;
                        } else {
                            //if there are not enough answers in other answers, generate random answers
                            $answers = [$correct_answer];
                            while (count($answers) < 4) {
                                $randomAnswer = LessonDetail::inRandomOrder()->first();
                                if (!in_array($randomAnswer->definition, $answers)) {
                                    array_push($answers, trim($randomAnswer->definition));
                                }
                            }
                            shuffle($answers);
                        }
                    }
                } catch (\Throwable $th) {
                    return response()->json([
                        'status' => 'error',
                        'status_code' => 500,
                        'message' => $th->getMessage()
                    ], 500);
                }
                $response['lesson_details'][] = [
                    'id' => $lessonDetail->id,
                    'question' => $question,
                    'answers' => $answers,
                    'correct_answer' => $correct_answer ?? '',
                    'relearn' => in_array($lessonDetail->id, $relearn) ? true : false
                ];
            }
            return response()->json($response);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function learn(Request $request, $lesson_id)
    {
        try {
            $reverse = $request->input('reverse', false); //request reverse term and definition
            $mixAnswers = $request->input('mix_answers', false); //request mix answer part
            $mixDetails = $request->input('mix_details', false); //request mix lesson details

            $lesson = Lesson::findOrFail($lesson_id);
            //get learned lesson details from learn table
            $learn = Learn::where('user_id', auth()->user()->id)->where('lesson_id', $lesson_id)->first();
            //get id of learned lesson details
            $learned = $learn ? explode(',', $learn->learned) : [];
            //get id of relearn lesson details
            $relearn = $learn ? explode(',', $learn->relearn) : [];
            // $lessonDetails = LessonDetail::where('lesson_id', $lesson_id)->whereNotIn('id', $learned)->whereNotIn('id', $relearn);
            $lessonDetails = [];
            //get relearn lesson details
            if ($relearn) {
                $lessonDetails = LessonDetail::whereIn('id', $relearn)->get();
            }
            if ($mixDetails) {
                //if learned and relearn lesson details are empty
                if (count($learned) === 0 && count($relearn) === 0) {
                    $lessonDetails = LessonDetail::where('lesson_id', $lesson_id)->inRandomOrder()->take(8)->get();
                } else {
                    $notLearn = LessonDetail::where('lesson_id', $lesson_id)->whereNotIn('id', $learned)->whereNotIn('id', $relearn)->inRandomOrder()->take(8 - count($lessonDetails))->get();
                    $lessonDetails = $lessonDetails->merge($notLearn);
                }
            } else {
                //if learned and relearn lesson details are empty
                if (count($learned) === 0 && count($relearn) === 0) {
                    $lessonDetails = LessonDetail::where('lesson_id', $lesson_id)->take(8)->get();
                } else {
                    $notLearn = LessonDetail::where('lesson_id', $lesson_id)->whereNotIn('id', $learned)->whereNotIn('id', $relearn)->take(8 - count($lessonDetails))->get();
                    $lessonDetails = $lessonDetails->merge($notLearn);
                }
            }
            $response = ['lesson_id' => $lesson_id, 'lesson_details' => []];
            foreach ($lessonDetails as $lessonDetail) {
                try {
                    $term = $lessonDetail->term;
                    $definition = $lessonDetail->definition;
                    if ($reverse) {
                        // check if the term and definition are not empty before swapping them
                        if (!empty($term) && !empty($definition)) {
                            $temp = $term;
                            $term = $definition;
                            $definition = $temp;
                        } else {
                            // if either term or definition is empty, set the response accordingly
                            return response()->json([
                                'status' => 'error',
                                'status_code' => 500,
                                'message' => 'One or both of the term and definition are empty.'
                            ], 500);
                        }
                    }
                    $answers = [];
                    // replace special characters with a space character
                    $term = preg_replace('/[\n\r\t]+/', ' ', $term);

                    // check if the term is a multiple choice question
                    if (preg_match('/^(.*?)\s*[A-Ma-m]\.\s*(.*)/is', $term, $matches)) {
                        $question = trim($matches[1]);
                        $options_str = $matches[2];
                        // split the options into arrays
                        $options = preg_split('/\s*[a-m]\.\s*/i', $options_str, -1, PREG_SPLIT_NO_EMPTY);
                        //check if $options might be missing any options like A, B, C, D

                        $answers = array_map('trim', $options);
                        if (count($answers) < 2) {
                            // if the number of options is less than 4, then the options are missing
                            return response()->json([
                                'status' => 'error',
                                'status_code' => 500,
                                'message' => 'Please recheck the options for the question "' . $question . '"' . ' in the lesson "' . $lesson->name . '" ,question_id: ' . $lessonDetail->id
                            ], 500);
                        }
                        //count characters of definition
                        $count = strlen($definition);
                        //if count > 1, then correct answer include more than 1 option
                        if ($count > 1) {
                            $correct_answer = [];
                            //loop through definition
                            for ($i = 0; $i < $count; $i++) {
                                //get character of definition
                                $char = substr($definition, $i, 1);
                                //get correct answer by character
                                switch (trim($char)) {
                                    case 'A':
                                    case 'a':
                                        $correct_answer[] = $answers[0];
                                        break;
                                    case 'B':
                                    case 'b':
                                        $correct_answer[] = $answers[1];
                                        break;
                                    case 'C':
                                    case 'c':
                                        $correct_answer[] = $answers[2];
                                        break;
                                    case 'D':
                                    case 'd':
                                        $correct_answer[] = $answers[3];
                                        break;
                                    case 'E':
                                    case 'e':
                                        $correct_answer[] = $answers[4];
                                        break;
                                    case 'F':
                                    case 'f':
                                        $correct_answer[] = $answers[5];
                                        break;
                                    case 'G':
                                    case 'g':
                                        $correct_answer[] = $answers[6];
                                        break;
                                    default:
                                        $correct_answer[] = '';
                                        break;
                                }
                            }
                            //convert correct answer to string
                            $correct_answer = trim(implode('|| ', $correct_answer));
                        }else {
                        switch (trim($definition)) {
                            case 'A':
                            case 'a':
                                $correct_answer = isset($answers[0]) ? $answers[0] : "Recheck this lesson detail";
                                break;
                            case 'B':
                            case 'b':
                                $correct_answer = isset($answers[1]) ? $answers[1] : (isset($answers[0]) ? $answers[0] : "Recheck this lesson detail");
                                break;
                            case 'C':
                            case 'c':
                                $correct_answer = isset($answers[2]) ? $answers[2] : (isset($answers[1]) ? $answers[1] : "Recheck this lesson detail");
                                break;
                            case 'D':
                            case 'd':
                                $correct_answer = isset($answers[3]) ? $answers[3] : (isset($answers[2]) ? $answers[2] : "Recheck this lesson detail");
                                break;
                            case 'E':
                            case 'e':
                                $correct_answer = $answers[4];
                                break;
                            case 'F':
                            case 'f':
                                $correct_answer = $answers[5];
                                break;
                            case 'G':
                            case 'g':
                                $correct_answer = $answers[6];
                                break;
                            default:
                                $correct_answer = '';
                                break;
                        }

                        $correct_answer = trim($correct_answer);
                    }
                        if ($mixAnswers) {
                            shuffle($answers);
                        }
                    }
                    // check if the term is a true/false question
                    else if (preg_match('/^(.*)\s*[a-z]\.\s*(True|False)/is', $term, $matches)) {
                        $question = trim($matches[1]);
                        $answers = array_map('trim', [$matches[2], $matches[3]]);

                        // determine the correct answer
                        $correct_answer = ($definition === 'True') ? 'True' : 'False';

                        if ($mixAnswers) {
                            shuffle($answers);
                        }
                    }
                    // if the term is neither multiple choice nor true/false
                    else {
                        $question = $term;
                        $correct_answer = trim($definition);

                        //get current $response and get the and $answers of other lessonDetail except true/false
                        $otherAnswers = $response['lesson_details'];
                        $otherAnswers = array_filter($otherAnswers, function ($item) {
                            return count($item['answers']) > 2;
                        });

                        //check if there are enough answers in other answers or not
                        if (count($otherAnswers) >= 3) {
                            //get random 3 answers from other otherAnswers
                            $otherAnswers = array_map(function ($item) {
                                return $item['answers'];
                            }, $otherAnswers);
                            $otherAnswers = array_merge(...$otherAnswers);
                            shuffle($otherAnswers);
                            $otherAnswers = array_slice($otherAnswers, 0, 3);

                            //add correct answer to otherAnswers
                            array_push($otherAnswers, $correct_answer);
                            // shuffle the answer options
                            shuffle($otherAnswers);
                            // lesson the answer options to the shuffled $otherAnswers
                            $answers = $otherAnswers;
                        } else {
                            //if there are not enough answers in other answers, generate random answers
                            $answers = [$correct_answer];
                            while (count($answers) < 4) {
                                $randomAnswer = LessonDetail::inRandomOrder()->first();
                                if (!in_array($randomAnswer->definition, $answers)) {
                                    array_push($answers, trim($randomAnswer->definition));
                                }
                            }
                            shuffle($answers);
                        }
                    }
                } catch (\Throwable $th) {
                    return response()->json([
                        'status' => 'error',
                        'status_code' => 500,
                        'message' => $th->getMessage()
                    ], 500);
                }
                $response['lesson_details'][] = [
                    'id' => $lessonDetail->id,
                    'question' => $question,
                    'answers' => $answers,
                    'correct_answer' => $correct_answer ?? '',
                    'relearn' => in_array($lessonDetail->id, $relearn) ? true : false
                ];
            }
            return response()->json($response);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function import(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'type' => 'required|in:text,file',
                'name' => 'required|string',
                'description' => 'required|string',
                'text' => 'required_if:type,text|nullable',
                'term_separator' => 'required_if:type,text',
                'set_separator' => 'required_if:type,text',
                'file' => 'required_if:type,file|mimes:xls,xlsx,csv',
            ]);
            $type = $request->input('type');
            // Tao lesson moi
            $lesson = new Lesson();
            $lesson->name = $request->name;
            $lesson->description = $request->description;
            $lesson->user_id = auth()->user()->id;
            $lesson->save();
            if ($type == 'text') {
                $raw_detail = explode($request->set_separator, $request->text);
                if (count($raw_detail) < 3) {
                    // delete lesson
                    $lesson->delete();
                    return response()->json([
                        'status' => 'error',
                        'status_code' => 400,
                        'message' => 'Text must have more than 3 sets'
                    ], 400);
                }
                foreach ($raw_detail as $item) {
                    try {
                        $raw = explode($request->term_separator, $item);
                        $term = $raw[0];
                        $definition = $raw[1];
                        $lesson->lessonDetail()->create([
                            'term' => $term,
                            'definition' => $definition
                        ]);
                    } catch (\Throwable $th) {
                        continue;
                    }
                }
            } else if ($type == 'file') {
                $file = $request->file('file');
                $file_extension = $file->getClientOriginalExtension();
                if ($file_extension == 'csv') {
                    $csv = array_map('str_getcsv', file($file));
                    // check data have more than 4 rows
                    if (count($csv) > 3) {
                        // check data have 2 columns
                        if (count($csv[0]) > 2) {
                            foreach ($csv as $key => $item) {
                                if ($key >= 1) {
                                    $lesson->lessonDetail()->create([
                                        'term' => $item[1],
                                        'definition' => $item[2]
                                    ]);
                                }
                            }
                        } else {
                            // delete lesson
                            $lesson->delete();
                            return response()->json([
                                'status' => 'error',
                                'status_code' => 400,
                                'message' => 'File must have more than 2 columns ( no,term, definition,... )'
                            ], 400);
                        }
                    } else {
                        // delete lesson
                        $lesson->delete();
                        return response()->json([
                            'status' => 'error',
                            'status_code' => 400,
                            'message' => 'File must have more than 4 rows'
                        ], 400);
                    }
                } else {
                    // if file is not csv file, return error
                    // delete lesson
                    $lesson->delete();
                    return response()->json([
                        'status' => 'error',
                        'status_code' => 400,
                        'message' => 'File must be csv file'
                    ], 400);
                }
            }
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Lesson created successfully',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function export(Request $request, $id): BinaryFileResponse|JsonResponse
    {
        try {
            $lesson = Lesson::findOrfail($id);
            $lessonData = $lesson->lessonDetail()->get()->toArray();
            if (empty($lessonData)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Lesson is empty'
                ], 400);
            }
            // write lesson data to file
            $file_name = 'set_' . $id . '_' . date('Ymd_His') . '.csv';
            $file = fopen($file_name, 'w');
            fputcsv($file, ['no', 'term', 'definition']);
            foreach ($lessonData as $line) {
                fputcsv($file, $line);
            }
            fclose($file);
            // lesson header
            $headers = [
                'Content-Type' => 'text/csv',
            ];
            // download file
            return response()->download($file_name, $file_name, $headers)->deleteFileAfterSend(true);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
