<el-dialog :visible.sync="imagePreview.show" style="text-align: center" :width="imagePreview.width + 'px'">
    <el-image :src="imagePreview.src" fit="contain"></el-image>
</el-dialog>