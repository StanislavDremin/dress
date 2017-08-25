<template>
    <div>

        <div class="sidebar-element__el-descs">
            <span class="title">Описание</span>
            <div class="desc">
                <p>Трендовое пальто Pompa нежно-персикового цвета силуэта кокон из шелковистой ворсовой ткани
                    нежного абрикосового оттенка. Модель имеет втачной длинный рукав, круглый вырез горловины,
                    карманы в фигурных подрезах и застежку на пришивные кнопки. Пальто украшено тамбурной
                    строчкой и декоративной подкладкой. </p>
            </div>
            <div class="hidden-desc" v-show="show">
                <p>Трендовое пальто Pompa нежно-персикового цвета силуэта кокон из шелковистой ворсовой ткани
                    нежного абрикосового оттенка. Модель имеет втачной длинный рукав, круглый вырез горловины,
                    карманы в фигурных подрезах и застежку на пришивные кнопки. Пальто украшено тамбурной
                    строчкой и декоративной подкладкой. </p>
            </div>
            <span class="btn-desc" @click="onClick">Развернуть <i class="fa fa-angle-down"
                                                                  aria-hidden="true"></i></span>
        </div>

    </div>
</template>

<script>
    import {TimelineMax} from "gsap";

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
                tl.fromTo('.hidden-desc', 1, {y: -40, opacity: 0}, { opacity: 1, ease: Circ.easeOut, y: 1});
                tl1.fromTo('.btn-desc', 1, {y: -40, opacity: 0}, { opacity: 1, ease: Circ.easeOut, y: 1});

            }
        }
    }
</script>