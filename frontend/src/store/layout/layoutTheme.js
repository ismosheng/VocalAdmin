import { defineStore } from 'pinia'
import { ref,computed,reactive,watch,onUnmounted } from 'vue'
import { theme as antdTheme } from 'ant-design-vue'
import { cloneDeep } from 'lodash-es'
import { defaultSettings, animations } from '@/settings/layoutTheme.js'
import { toggleClass } from '@/utils/dom.js'

export const useLayoutThemeStore = defineStore(
  'layoutTheme',
  () => {
    // 定义响应式引用存储屏幕宽度
    const screenWidth=ref(window.innerWidth)
    // 定义计算属性来判断是否为移动设备
    const isMobile=computed(() => screenWidth.value<=768)

    // 监听窗口大小变化来更新屏幕宽度
    const updateScreenWidth=() => {
      screenWidth.value=window.innerWidth
    }
    window.addEventListener('resize',updateScreenWidth)

    // 在 store 销毁时移除事件监听器
    onUnmounted(() => {
      window.removeEventListener('resize',updateScreenWidth)
    })
    const layoutSetting = reactive({ ...defaultSettings })
    const theme = reactive({
      algorithm: antdTheme[layoutSetting.algorithm],
      token: {
        colorPrimary: layoutSetting.colorPrimary,
        borderRadius: layoutSetting.borderRadius
      }
    })

    const changeNprogressBg = () => {
      const htmlDom = document.querySelector('html')
      htmlDom.style.setProperty('--nprogress-color', layoutSetting.colorPrimary)
    }
    changeNprogressBg()

    watch(
      () => cloneDeep(layoutSetting),
      (newVal, oldVal) => {
        if (newVal.algorithm !== oldVal.algorithm) {
          if (layoutSetting.algorithm === 'darkAlgorithm') {
            toggleClass(document.documentElement, 'dark', true)
          } else {
            toggleClass(document.documentElement, 'dark', false)
          }
          theme.algorithm = antdTheme[layoutSetting.algorithm]
        }
        if (newVal.colorPrimary !== oldVal.colorPrimary) {
          theme.token.colorPrimary = layoutSetting.colorPrimary
          changeNprogressBg()
        }
        if (newVal.colorWeak !== oldVal.colorWeak) {
          if (layoutSetting.colorWeak) {
            toggleClass(document.documentElement, 'color-weak', true)
          } else {
            toggleClass(document.documentElement, 'color-weak', false)
          }
        }
        if (newVal.grayMode !== oldVal.grayMode) {
          if (layoutSetting.grayMode) {
            toggleClass(document.documentElement, 'gray-mode', true)
          } else {
            toggleClass(document.documentElement, 'gray-mode', false)
          }
        }
        if (newVal.animation !== oldVal.animation) {
          layoutSetting.animationDirection = animations.find(
            (item) => item.animation === layoutSetting.animation
          ).options[0]
        }
        if (newVal.borderRadius !== oldVal.borderRadius) {
          theme.token.borderRadius = layoutSetting.borderRadius
        }
      },
      {
        deep: true
      }
    )

    const updateLayoutSetting = (settings) => {
      Object.assign(layoutSetting, settings)
    }

    const border = computed(() => {
      let border = 'none'
      switch (layoutSetting.algorithm) {
        case 'defaultAlgorithm':
        case 'compactAlgorithm':
          switch (layoutSetting.menuTheme) {
            case 'light':
              border = '1px solid #eee'
              break
            case 'dark':
              switch (layoutSetting.navThemeFollowMenu) {
                case true:
                  border = '1px solid #001529'
                  break
                case false:
                  border = '1px solid #eee'
                  break
                default:
                  break
              }
              break
            default:
              break
          }
          break
        case 'darkAlgorithm':
          break
        default:
          break
      }
      return border
    })

    const titleColor = computed(() => {
      let titleColor = '#fff'
      switch (layoutSetting.layout) {
        case 'sidemenu':
          switch (layoutSetting.algorithm) {
            case 'defaultAlgorithm':
            case 'compactAlgorithm':
              switch (layoutSetting.menuTheme) {
                case 'light':
                  titleColor = '#000'
                  break
                case 'dark':
                  break
                default:
                  break
              }
              break
            case 'darkAlgorithm':
              switch (layoutSetting.menuTheme) {
                case 'light':
                  break
                case 'dark':
                  break
                default:
                  break
              }
              break
            default:
              break
          }
          break
        case 'topmenu':
          switch (layoutSetting.algorithm) {
            case 'defaultAlgorithm':
            case 'compactAlgorithm':
              switch (layoutSetting.menuTheme) {
                case 'light':
                  titleColor = '#000'
                  break
                case 'dark':
                  break
                default:
                  break
              }
              break
            case 'darkAlgorithm':
              switch (layoutSetting.menuTheme) {
                case 'light':
                  break
                case 'dark':
                  break
                default:
                  break
              }
              break
            default:
              break
          }
          break
        case 'mixinmenu':
          switch (layoutSetting.algorithm) {
            case 'defaultAlgorithm':
            case 'compactAlgorithm':
              switch (layoutSetting.menuTheme) {
                case 'light':
                  titleColor = '#000'
                  break
                case 'dark':
                  switch (layoutSetting.navThemeFollowMenu) {
                    case true:
                      break
                    case false:
                      titleColor = '#000'
                      break
                    default:
                      break
                  }
                  break
                default:
                  break
              }
              break
            case 'darkAlgorithm':
              switch (layoutSetting.menuTheme) {
                case 'light':
                  break
                case 'dark':
                  break
                default:
                  break
              }
              break
            default:
              break
          }
          break
        default:
          break
      }
      return titleColor
    })

    const headerBackground = computed(() => {
      let headerBackground = '#fff'
      switch (layoutSetting.layout) {
        case 'sidemenu':
          switch (layoutSetting.algorithm) {
            case 'defaultAlgorithm':
            case 'compactAlgorithm':
              switch (layoutSetting.menuTheme) {
                case 'light':
                  break
                case 'dark':
                  switch (layoutSetting.navThemeFollowMenu) {
                    case true:
                      headerBackground = '#001529'
                      break
                    case false:
                      break
                    default:
                      break
                  }
                  break
                default:
                  break
              }
              break
            case 'darkAlgorithm':
              switch (layoutSetting.menuTheme) {
                case 'light':
                  headerBackground = '#141414'
                  break
                case 'dark':
                  headerBackground = '#001529'
                  break
                default:
                  break
              }
              break
            default:
              break
          }
          break
        case 'topmenu':
          switch (layoutSetting.algorithm) {
            case 'defaultAlgorithm':
            case 'compactAlgorithm':
              switch (layoutSetting.menuTheme) {
                case 'light':
                  break
                case 'dark':
                  headerBackground = '#001529'
                  break
                default:
                  break
              }
              break
            case 'darkAlgorithm':
              switch (layoutSetting.menuTheme) {
                case 'light':
                  headerBackground = '#141414'
                  break
                case 'dark':
                  headerBackground = '#001529'
                  break
                default:
                  break
              }
              break
            default:
              break
          }
          break
        case 'mixinmenu':
          switch (layoutSetting.algorithm) {
            case 'defaultAlgorithm':
            case 'compactAlgorithm':
              switch (layoutSetting.menuTheme) {
                case 'light':
                  break
                case 'dark':
                  switch (layoutSetting.navThemeFollowMenu) {
                    case true:
                      headerBackground = '#001529'
                      break
                    case false:
                      break
                    default:
                      break
                  }
                  break
                default:
                  break
              }
              break
            case 'darkAlgorithm':
              switch (layoutSetting.menuTheme) {
                case 'light':
                  headerBackground = '#141414'
                  break
                case 'dark':
                  headerBackground = '#001529'
                  break
                default:
                  break
              }
              break
            default:
              break
          }
          break
        default:
          break
      }
      return headerBackground
    })
    const headerColor = computed(() => (headerBackground.value === '#fff' ? '#000' : '#fff'))

    return {

      screenWidth,
      isMobile,
      layoutSetting,
      theme,
      border,
      titleColor,
      headerBackground,
      headerColor,
      updateLayoutSetting
    }
  },
  {
    persist: true
  }
)
