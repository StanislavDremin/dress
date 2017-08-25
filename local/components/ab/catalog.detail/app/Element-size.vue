<template>
    <div>
    
        <el-popover ref="popover1" placement="top" popper-class="popOverSizes" trigger="hover" v-model="visible1">
            <div style="text-align: right; margin: 0">
                <span>Обхват груди , в см:
                    <b>82-86</b>
                </span>
                <span>Обхват талии , в см:
                    <b>62-66</b>
                </span>
                <span>Обхват бедер, в см:
                    <b>90-94</b>
                </span>
                <span>Обхват бедер, в см:
                    <b>90-94</b>
                </span>
            </div>
        </el-popover>
    
        <el-popover ref="popover2" placement="top" popper-class="popOverSizes" trigger="hover" v-model="visible2">
            <div style="text-align: right; margin: 0">
                <span>Обхват груди , в см:
                    <b>82-86</b>
                </span>
                <span>Обхват талии , в см:
                    <b>62-66</b>
                </span>
                <span>Обхват бедер, в см:
                    <b>90-94</b>
                </span>
                <span>Обхват бедер, в см:
                    <b>90-94</b>
                </span>
            </div>
        </el-popover>
    
        <el-popover ref = "popover3" placement = "top" popper-class = "popOverSizes" trigger = "hover" v-model="visible3">
            <div style="text-align: right; margin: 0">
                <span>Обхват груди , в см:
                    <b>82-86</b>
                </span>
                <span>Обхват талии , в см:
                    <b>62-66</b>
                </span>
                <span>Обхват бедер, в см:
                    <b>90-94</b>
                </span>
                <span>Обхват бедер, в см:
                    <b>90-94</b>
                </span>
            </div>
        </el-popover>
    
        <el-popover ref="popover4" placement="top" popper-class="popOverSizes" trigger="hover" v-model="visible4">
            <div style="text-align: right; margin: 0">
                <span>Обхват груди , в см:
                    <b>82-86</b>
                </span>
                <span>Обхват талии , в см:
                    <b>62-66</b>
                </span>
                <span>Обхват бедер, в см:
                    <b>90-94</b>
                </span>
                <span>Обхват бедер, в см:
                    <b>90-94</b>
                </span>
            </div>
        </el-popover>
    
        <div class="sidebar-element__el-sizes-desc">
            <div class="sizes-hero">
                <span>Российский размер</span>
                <a href="#">Как узнать свой размер?</a>
            </div>
            <div class="sizes-list">
                <span class="size isActive" v-popover:popover1>41</span>
                <span class="size" v-popover:popover2>42</span>
                <span class="size" v-popover:popover3>43</span>
                <span class="size" v-popover:popover4>44</span>
            </div>
        </div>
    
    </div>
</template>

<script>
export default {
    data() {
        return {
            visible1: false,
            visible2: false,
            visible3: false,
            visible4: false,
        }
    }
}

</script>

<style lang="sass">
.popOverSizes
    width: 170px
    display: flex
    justify-content: center
    align-items: center
    text-align: center
    flex-direction: column
    font-family: 'Roboto Light',sans-serif
    font-size: 12px
    color: #adafae
    line-height: 1.8
    b
        color: #000

</style>
