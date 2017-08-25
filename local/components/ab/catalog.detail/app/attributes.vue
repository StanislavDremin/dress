<template>
    <div>
    
        <div class="sidebar-element__el-attributes">
            <span class="title">Характеристики</span>
            <div class="attribute">
                <span class="name">Основная ткань</span>
                <span class="name-desc">шерсть 92 %, нейлон 8 %</span>
            </div>
            <div class="attribute">
                <span class="name">Покрой/Силуэт</span>
                <span class="name-desc">кокон</span>
            </div>
            <div class="attribute">
                <span class="name">Подкладка</span>
                <span class="name-desc">полиэстер 55 %, вискоза 45 %</span>
            </div>
            <div class="attribute">
                <span class="name">Вырез горловины</span>
                <span class="name-desc">круглый</span>
            </div>
            <div class="attribute">
                <span class="name">Тип рукава</span>
                <span class="name-desc">втачной с вытачкой</span>
            </div>
            <div class="attribute">
                <span class="name">Длина рукава (164 см)</span>
                <span class="name-desc">61,0</span>
            </div>
            <div class="attribute">
                <span class="name">Длина рукава (170 см)</span>
                <span class="name-desc">63,0</span>
            </div>
            <div class="attribute">
                <span class="name">Ширина рукава (охват плеча)</span>
                <span class="name-desc">37,0</span>
            </div>
    
            <div class="hidden-attribute" v-show="show">
                <div class="attribute">
                    <span class="name">Основная ткань</span>
                    <span class="name-desc">шерсть 92 %, нейлон 8 %</span>
                </div>
                <div class="attribute">
                    <span class="name">Основная ткань</span>
                    <span class="name-desc">шерсть 92 %, нейлон 8 %</span>
                </div>
            </div>
    
            <span @click="onClick" class="btn-descs">Развернуть
                <i class=" fa fa-angle-down" aria-hidden="true"></i>
            </span>
    
        </div>
    
    </div>
</template>

<script>
import { TimelineMax } from "gsap";


let tl = new TimelineMax();
let tl1 = new TimelineMax();

export default {
    data() {
        return {
            show: false
        }
    },
    methods: {
        onClick() {
            this.show = !this.show;
            tl.fromTo('.hidden-attribute', 1, { y: -40, opacity: 0 }, { opacity: 1, ease: Circ.easeOut, y: 1 });
            tl1.fromTo('.btn-descs', 1, { y: -40, opacity: 0 }, { opacity: 1, ease: Circ.easeOut, y: 1 });

        }
    }
}
</script>

