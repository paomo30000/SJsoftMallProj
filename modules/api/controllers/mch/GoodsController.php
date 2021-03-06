<?php
/**
 * @link http://www.zjhejiang.com/
 * @copyright Copyright (c) 2018 浙江禾匠信息科技有限公司
 * @author Lu Wei
 *
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/3/22
 * Time: 13:38
 */


namespace app\modules\api\controllers\mch;


use app\models\Goods;
use app\modules\api\models\mch\GoodsListForm;

class GoodsController extends Controller
{
    public function actionList()
    {
        $form = new GoodsListForm();
        $form->attributes = \Yii::$app->request->get();
        $form->mch_id = $this->mch->id;
        return $this->renderJson($form->search());
    }

    public function actionSetStatus($id, $status)
    {
        $model = Goods::findOne([
            'id' => $id,
            'mch_id' => $this->mch->id,
        ]);
        if (!$model) {
            return $this->renderJson([
                'code' => 1,
                'msg' => '商品不存在',
            ]);
        }
        $model->status = $status == 1 ? 1 : 0;
        if ($model->status == 1 && !$model->getNum()) {
            return $this->renderJson([
                'code' => 1,
                'msg' => '商品库存为0上架失败，请先设置商品库存',
            ]);
        }
        if ($model->save()) {
            return $this->renderJson([
                'code' => 0,
                'msg' => $model->status == 1 ? '上架成功' : '下架成功',
            ]);
        } else {
            return $this->renderJson([
                'code' => 0,
                'msg' => $status == 1 ? '上架失败' : '下架失败',
            ]);
        }
    }

    public function actionDelete($id)
    {
        $model = Goods::findOne([
            'id' => $id,
            'mch_id' => $this->mch->id,
        ]);
        if (!$model) {
            return $this->renderJson([
                'code' => 1,
                'msg' => '商品不存在',
            ]);
        }
        $model->is_delete = 1;
        $model->save();
        return $this->renderJson([
            'code' => 0,
            'msg' => '删除成功',
        ]);
    }

}