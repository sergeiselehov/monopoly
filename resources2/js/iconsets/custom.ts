import { h } from 'vue'
import type { IconSet, IconAliases, IconProps } from 'vuetify'

const aliases: IconAliases = {
    collapse: '...',
}

const custom: IconSet = {
    component: (props: IconProps) => h(...),
}

export { aliases, custom }
