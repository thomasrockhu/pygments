<?php
namespace Ramsey\Pygments\Test;

use PHPUnit\Framework\TestCase;
use Ramsey\Pygments\Pygments;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Process;

class PygmentsTest extends TestCase
{
    /**
     * @var Pygments
     */
    protected $pygments;

    protected function setUp(): void
    {
        $this->pygments = new Pygments(getenv('PYGMENTIZE_PATH'));
    }

    /**
     * @dataProvider provideSamples
     */
    public function testHighlight($input, $expected, $expectedL, $expectedG, $lexer)
    {
        $this->assertEquals($expectedG, $this->pygments->highlight($input, null, 'html'));
        $this->assertEquals($expected, $this->pygments->highlight($input, $lexer, 'html'));
        $this->assertEquals($expectedL, $this->pygments->highlight($input, null, 'html', ['linenos' => 1]));
    }

    /**
     * @dataProvider provideCss
     */
    public function testGetCss($expected, $expectedA, $style)
    {
        $this->assertEquals($expected, $this->pygments->getCss($style));
        $this->assertEquals($expectedA, $this->pygments->getCss($style, '.syntax'));
    }

    public function testGetLexers()
    {
        $lexers = $this->pygments->getLexers();

        $this->assertArrayHasKey('python', $lexers);
    }

    public function testGetFormatters()
    {
        $formatters = $this->pygments->getFormatters();

        $this->assertArrayHasKey('html', $formatters);
    }

    public function testGetStyles()
    {
        $styles = $this->pygments->getStyles();

        $this->assertArrayHasKey('monokai', $styles);
    }

    public function testGetOutputThrowsExceptionWhenProcessNotSuccessful()
    {
        $process = \Mockery::mock(Process::class);
        $process->shouldReceive('stop');
        $process->shouldReceive('run')->once();
        $process->shouldReceive('isSuccessful')->once()->andReturn(false);
        $process->shouldReceive('getErrorOutput')->once()->andReturn('foobar');

        $getOutput = new \ReflectionMethod(Pygments::class, 'getOutput');
        $getOutput->setAccessible(true);

        $this->expectException('RuntimeException');
        $this->expectExceptionMessage('foobar');

        $getOutput->invoke($this->pygments, $process);
    }

    public function testGuessLexer()
    {
        $this->assertEquals('php', $this->pygments->guessLexer('index.php'));
        $this->assertEquals('go', $this->pygments->guessLexer('main.go'));
    }

    public function provideSamples()
    {
        $finder = new Finder();
        $finder
            ->in(__DIR__ . '/fixtures/pygments-' . getenv('PYGMENTIZE_VERSION') . '/example')
            ->name("*.in")
            ->files()
            ->ignoreVCS(true);

        $samples = [];

        /* @var \Symfony\Component\Finder\SplFileInfo $file */
        foreach ($finder as $file) {
            $samples[] = [
                $file->getContents(),
                file_get_contents(str_replace('.in', '.out', $file->getPathname())),
                file_get_contents(str_replace('.in', '.linenos.out', $file->getPathname())),
                file_get_contents(str_replace('.in', '.guess.out', $file->getPathname())),
                preg_replace('/\..*/', '', $file->getFilename()),
            ];
        }

        return $samples;
    }

    public function provideCss()
    {
        $finder = new Finder();
        $finder
            ->in(__DIR__ . '/fixtures/pygments-' . getenv('PYGMENTIZE_VERSION') . '/css')
            ->files()
            ->ignoreVCS(true)
            ->name('*.css')
            ->notName('*.prefix.css');

        $css = [];

        /* @var \Symfony\Component\Finder\SplFileInfo $file */
        foreach ($finder as $file) {
            $css[] = [
                $file->getContents(),
                file_get_contents(str_replace('.css', '.prefix.css', $file->getPathname())),
                str_replace('.css', '', $file->getFilename()),
            ];
        }

        return $css;
    }
}
