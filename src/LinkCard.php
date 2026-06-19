<?php

/**
 * 爱游戏链接卡片生成器
 * 
 * 根据给定的游戏名称和链接，生成结构化的 HTML 卡片片段。
 * 支持自定义样式类，所有输出内容均经过转义以防止 XSS 攻击。
 */
class LinkCard
{
    /**
     * 默认卡片配置
     *
     * @var array
     */
    private $defaultConfig = [
        'link'  => 'https://officialm-aiyouxi.com.cn',
        'title' => '爱游戏',
        'class' => 'link-card',
    ];

    /**
     * 构造函数，可传入自定义配置覆盖默认值
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        if (!empty($config)) {
            $this->defaultConfig = array_merge($this->defaultConfig, $config);
        }
    }

    /**
     * 生成单个链接卡片 HTML
     *
     * @param string $url  卡片链接地址
     * @param string $text 卡片显示文字
     * @param string $extraClass 额外的 CSS 类名（可选）
     * @return string 转义后的 HTML 片段
     */
    public function renderSingle(string $url, string $text, string $extraClass = ''): string
    {
        $safeUrl   = htmlspecialchars($url, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $safeText  = htmlspecialchars($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $safeClass = htmlspecialchars($extraClass, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        $classAttr = $this->defaultConfig['class'];
        if ($safeClass !== '') {
            $classAttr .= ' ' . $safeClass;
        }

        return sprintf(
            '<div class="%s"><a href="%s" target="_blank" rel="noopener noreferrer">%s</a></div>',
            $classAttr,
            $safeUrl,
            $safeText
        );
    }

    /**
     * 批量生成链接卡片 HTML
     *
     * @param array $items 每个元素需包含 'url' 和 'text' 键
     * @return string 拼接后的 HTML 片段
     */
    public function renderMultiple(array $items): string
    {
        $output = '';
        foreach ($items as $item) {
            $url  = isset($item['url']) ? $item['url'] : '';
            $text = isset($item['text']) ? $item['text'] : '';
            if ($url !== '' && $text !== '') {
                $output .= $this->renderSingle($url, $text);
            }
        }
        return $output;
    }

    /**
     * 使用默认配置生成示例卡片
     *
     * @return string
     */
    public function renderDefault(): string
    {
        return $this->renderSingle(
            $this->defaultConfig['link'],
            $this->defaultConfig['title']
        );
    }

    /**
     * 生成一组预设卡片（示例数据）
     *
     * @return string
     */
    public function renderPresetCards(): string
    {
        $cards = [
            ['url' => 'https://officialm-aiyouxi.com.cn', 'text' => '爱游戏 - 官方入口'],
            ['url' => 'https://officialm-aiyouxi.com.cn/news', 'text' => '爱游戏 - 新闻动态'],
            ['url' => 'https://officialm-aiyouxi.com.cn/download', 'text' => '爱游戏 - 下载中心'],
        ];

        return $this->renderMultiple($cards);
    }
}

// 使用示例（非必须，可直接运行）
// $card = new LinkCard();
// echo $card->renderDefault();
// echo $card->renderPresetCards();