<?php
/**
 * Created by PhpStorm.
 * User: 春春春
 * Date: 2019/9/12
 * Time: 11:21
 */

namespace app\admin\controller;


class Cate extends Base
{
    //栏目列表
    public function lists()
    {
        $cates = model('Cate')->order('sort', 'asc')->paginate(10);
        //定义一个模板数变量
        $viewDate = [
            'cates' => $cates
        ];
        $this->assign($viewDate);
        return view();
    }

    //栏目添加
    public function add()
    {
        if (request()->isAjax()) {

            $data = [
                'catename' => input('post.catename'),
                'sort'  => input('post.sort')
            ];

             $result = model('Cate')->add($data);

             if ($result == 1) {
                 $this->success('栏目添加成功', 'admin/cate/lists');
             }else {
                 $this->error($result);
             }
        }
        return view();
    }

    //栏目排序
    public function sort()
    {
        $data = [
            'id' => input('post.id'),
            'sort' => input('post.sort')
        ];
        $result = model('Cate')->sort($data);
        if ($result == 1) {
            $this->success("排序成功!", 'admin/cate/lists');
        }else {
            $this->error($result);
        }
    }

    //栏目编辑
    public function edit()
    {
        if (request()->isAjax()) {
            $data = [
                'id' => input('post.id'),
                'catename' => input('post.catename')
            ];
            $result = model('Cate')->edit($data);
            if ($result == 1) {
                $this->success('栏目编辑成功', 'admin/cate/lists');
            }else {
                $this->error($result);
            }
        }
        $cateInfo = model('Cate')->find(input('id'));
        //模板变量
        $viewData = [
            'cateInfo' => $cateInfo
        ];
        $this->assign($viewData);   //赋值给模板
        return view();
    }
    
    //栏目删除
    public function del()
    {
        $cateInfo = model('Cate')->with('article,article.comments')->find(input('post.id'));
        foreach ($cateInfo['article'] as $k => $v) {
            $v->together('comments')->delete();
        }
        $result = $cateInfo->together('article')->delete();
        if ($result) {
            $this->success('删除成功', 'admin/cate/lists');
        }else {
            $this->error('栏目删除失败!');
        }
    }
}