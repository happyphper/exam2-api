<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuestionResult;
use App\Models\TestResult;
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
}
