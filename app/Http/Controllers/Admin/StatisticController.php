<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuestionResult;
use App\Models\TestResult;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    /**
     * 考试成绩分布
     *
     * @param Request $request
     * @return
     */
    public function gradeDistribution(Request $request)
    {
        $data = TestResult::where($request->only(['group_id', 'test_id']))->get(['score']);

        $stat = [
            ['name' => '60以下', 'label' => '60以下', 'value' => $data->where('score', '<', 60)->count()],
            ['name' => '60-69', 'label' => '60-69', 'value' => $data->where('score', '>', 59)->where('score', '<', 69)->count()],
            ['name' => '70-79', 'label' => '70-79', 'value' => $data->where('score', '>', 69)->where('score', '<', 79)->count()],
            ['name' => '80-89', 'label' => '80-89', 'value' => $data->where('score', '>', 79)->where('score', '<', 89)->count()],
            ['name' => '90-100', 'label' => '90-100', 'value' => $data->where('score', '>', 89)->where('score', '<=', 100)->count()],
        ];

        $headers = array_pluck($stat, 'name');
        $values = array_pluck($stat, 'value');

        return $this->response->array([
            'data' => $stat,
            'meta' => [
                'headers' => $headers,
                'values' => $values
            ]
        ]);
    }

    /**
     * 错题统计
     *
     * @param Request $request
     * @return mixed
     */
    public function errorQuestion(Request $request)
    {
        $data = QuestionResult::with('question:id,title')->where($request->only(['group_id', 'test_id']))
            ->where(['is_right' => false])
            ->selectRaw('question_id, count(*) as error_count')
            ->groupBy('question_id')->orderBy('error_count', 'desc')->get();

        $headers = $data->map(function ($item) {
            return $item->question->title;
        });
        $values = $data->pluck('error_count');

        return $this->response->array([
            'data' => $data,
            'meta' => [
                'headers' => $headers,
                'values' => $values
            ]
        ]);
    }

    /**
     * 用户成绩曲线图
     *
     * @param Request $request
     * @return mixed
     */
    public function userGradeCurve(Request $request)
    {
        $data = TestResult::where('user_id', $request->user_id)->orderBy('created_at', 'desc')->get(['score', 'created_at']);

        $headers = $data->pluck('created_at')->map(function ($item) {
            return $item->toDateString();
        });

        $values = $data->pluck('score');

        return $this->response->array([
            'data' => $data,
            'meta' => [
                'headers' => $headers,
                'values' => $values
            ]
        ]);
    }

    /**
     * 用户成绩数据
     *
     * @param Request $request
     * @return
     */
    public function userGradeData(Request $request)
    {
        $data = TestResult::when($request->course_id, function ($query) use ($request) {
            return $query->where($request->only('course_id'));
        })->when($request->group_id, function ($query) use ($request) {
            return $query->where($request->only('group_id'));
        })->when($request->created_at, function ($query) use ($request) {
            $date = $request->input('created_at');
            $dataArray = collect($date)->map(function ($item) { return Carbon::parse($item); })->toArray();
            return $query->whereBetween('created_at', $dataArray);
        })->with('user:id,name')->with('course:id,title')->with('group:id,name')->orderBy('user_id')->get();

        $data = $data->groupBy('user_id')->values()->toArray();

        return $this->response->array(compact('data'));
    }
}
