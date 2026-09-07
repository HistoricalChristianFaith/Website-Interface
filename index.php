<?php
// Home page — a Screwtape frame around a timeline of the witnesses
// "spread out through all time," and the C. S. Lewis case for reading old books.
// This is the site's landing page (served at "/"); it also carries the "about"
// content, so there is no separate about page. A random-verse link (the old
// homepage behavior) is preserved in the intro and at /random.

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="HCF Bible">
    <meta name="theme-color" content="#080d15">
    <link rel="manifest" href="/manifest.json">
    <title>Historical Christian Faith — the early Christian witnesses, searchable</title>
    <meta name="description" content="Three public-domain databases of the early Christian witnesses — their writings, their commentary verse by verse, and doctrine traced across the centuries. Free to read, search, and build upon.">
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,600;0,700;1,400;1,600&display=swap" rel="stylesheet">
    <link href="/bible-view.css?v=4" rel="stylesheet">
    <style>
        :root { --tl-serif: 'Lora', var(--serif); }
        body { background: var(--bg-0); color: var(--fg-0); font-family: var(--sans); text-wrap: pretty; }
        .about-page { padding: 0 0 96px; }
        .wrap { max-width: 780px; margin: 0 auto; padding: 0 20px; }

        /* ---- Intro ---- */
        .intro { padding-top: 72px; }
        .eyebrow { font-family: var(--tl-serif); font-size: 12px; letter-spacing: 0.18em;
            text-transform: uppercase; color: var(--gold); font-feature-settings: 'tnum'; }
        .intro h1 { font-family: var(--tl-serif); font-weight: 600; font-size: clamp(38px, 7vw, 60px);
            line-height: 1.06; letter-spacing: -0.02em; margin: 14px 0 0; color: var(--fg-0); }
        .rule { height: 1px; background: var(--line-soft); margin: 32px 0; border: 0; }

        /* ---- Section divider: closes the open, public-domain intro before the
               cold Screwtape bracket. A pair of hairlines meeting at a single
               gold lozenge — the one warm note handed forward to the timeline. ---- */
        .divider { border: 0; max-width: 780px; margin: 56px auto 0; padding: 0 20px;
            display: grid; grid-template-columns: 1fr auto 1fr; align-items: center; gap: 20px; }
        .divider::before, .divider::after { content: ''; height: 1px; background: var(--line-soft); }
        .divider-mark { width: 7px; height: 7px; transform: rotate(45deg);
            background: var(--gold-soft); border: 1px solid var(--gold-line); }
        .lead { font-family: var(--tl-serif); font-size: 18px; line-height: 1.7; color: var(--fg-1);
            text-align: justify; hyphens: auto; margin: 0 0 18px; }
        .lead:last-child { margin-bottom: 0; }
        .lead strong { color: var(--fg-0); font-weight: 600; }
        .lead a { color: var(--gold); text-underline-offset: 3px; }

        /* ---- Screwtape frame: the cold, grey bracket around the gold witnesses.
               No gold here — the demon speaks in a recessed, desaturated panel so the
               timeline's gold spine reads as light breaking into the dark. ---- */
        .pull { margin: 0; border-left: 2px solid var(--line);
            background: var(--bg-1); padding: 22px 28px; border-radius: 2px; }
        .pull p { font-family: var(--tl-serif); font-weight: 400; font-size: clamp(21px, 3vw, 28px);
            line-height: 1.42; margin: 0; color: var(--fg-1); }
        .pull footer { font-size: 13px; letter-spacing: 0.04em; margin-top: 20px; color: var(--fg-2); }
        .pull-turn { width: 40px; height: 1px; background: var(--line); margin: 22px 0; }
        .screwtape { padding: 64px 0 8px; }

        /* ---- "Made visible" divider ---- */
        .made-visible { text-align: center; padding: 56px 20px 8px; max-width: 780px; margin: 0 auto; }
        .made-visible .eyebrow { display: block; }
        .made-visible .drop { width: 1px; height: 48px; background: var(--line-soft); margin: 20px auto 0; }

        /* ---- Timeline ---- */
        .timeline { max-width: 980px; margin: 0 auto; padding: 0 20px; }
        .tl-era { display: grid; align-items: center; gap: 18px; grid-template-columns: 1fr auto 1fr;
            padding: 48px 0 32px; }
        .tl-era-line { height: 1px; background: var(--line-soft); }
        .tl-era-label { font-family: var(--tl-serif); font-size: 13px; letter-spacing: 0.16em;
            text-transform: uppercase; color: var(--fg-2); font-feature-settings: 'tnum'; white-space: nowrap; }
        .tl-row { display: grid; grid-template-columns: 120px 1px 1fr; column-gap: 32px; }
        .tl-yearcol { position: relative; }
        .tl-year { position: sticky; top: 72px; text-align: right; font-family: var(--tl-serif);
            font-feature-settings: 'tnum'; font-size: clamp(26px, 4vw, 40px); font-weight: 600;
            line-height: 1; color: var(--gold); }
        .tl-spine { position: relative; background: var(--line); }
        .tl-dot { position: absolute; left: -3.5px; top: 14px; width: 8px; height: 8px; border-radius: 50%;
            background: var(--bg-0); border: 1px solid var(--gold); }
        .tl-body { padding: 0 0 56px; min-width: 0; }
        .tl-name { font-family: var(--tl-serif); font-weight: 600; font-size: clamp(22px, 3vw, 27px);
            line-height: 1.15; margin: 0 0 20px; color: var(--fg-0); }
        /* Each witness's name links out to his article (Wikipedia, etc.).
           Quiet by default so the timeline reads as prose; gold on hover, in
           step with the citation links below each quote. */
        .tl-name a { color: inherit; text-decoration: none;
            border-bottom: 1px solid transparent; transition: color .15s, border-color .15s; }
        .tl-name a:hover, .tl-name a:focus-visible {
            color: var(--gold); border-bottom-color: var(--gold-line); }
        .tl-quote { border-left: 1px solid var(--gold-line); padding: 2px 0 2px 20px; margin: 0 0 22px; }
        .tl-text { font-family: var(--tl-serif); font-size: 16.5px; line-height: 1.68; margin: 0;
            text-align: justify; hyphens: auto; color: var(--fg-1); }
        .tl-quote p.tl-text + p.tl-text { margin-top: 15px; }
        .tl-cite { font-size: 12.5px; font-style: italic; color: var(--fg-2); margin-top: 9px; }
        .tl-cite a { color: inherit; text-decoration: none;
            border-bottom: 1px solid var(--gold-line); transition: color .15s, border-color .15s; }
        .tl-cite a:hover { color: var(--gold); border-bottom-color: var(--gold); }


        /* ---- Close ---- */
        .close { max-width: 780px; margin: 0 auto; padding: 72px 20px 0; text-align: center; }
        .close .quote { font-family: var(--tl-serif); font-style: italic; color: var(--fg-1);
            font-size: 18px; line-height: 1.7; margin: 0 0 8px; }
        .close .by { color: var(--fg-2); font-family: var(--tl-serif); }
        .close .by a { color: inherit; text-decoration: none;
            border-bottom: 1px solid var(--gold-line); transition: color .15s, border-color .15s; }
        .close .by a:hover { color: var(--gold); border-bottom-color: var(--gold); }

        .footer { background: var(--bg-1); color: var(--fg-2); padding: 2rem 0; margin-top: 4rem;
            border-top: 1px solid var(--line-soft); }
        .footer a { color: var(--gold); }

        /* Portrait attribution (satisfies CC BY-SA where used) */
        .credits { max-width: 620px; margin: 24px auto 0; text-align: left; }
        .credits summary { cursor: pointer; text-align: center; color: var(--fg-2);
            letter-spacing: 0.1em; text-transform: uppercase; font-size: 11px; list-style: none; }
        .credits summary::-webkit-details-marker { display: none; }
        .credits summary::after { content: ' +'; }
        .credits[open] summary::after { content: ' \2212'; }
        .credits p, .credits ul { font-size: 12px; line-height: 1.6; color: var(--fg-2); }
        .credits ul { padding-left: 18px; margin: 10px 0 0; }
        .credits li { margin: 6px 0; }
        .credits strong { color: var(--fg-1); font-weight: 600; }
        .credits a { color: var(--gold); }

        @media (max-width: 600px) {
            .tl-row { grid-template-columns: 50px 1px 1fr; column-gap: 16px; }
            .tl-year { top: 66px; font-size: 20px; }
            .tl-dot { left: -3.5px; }
            .tl-text { text-align: left; }
            .lead { text-align: left; }
        }

        /* ============================================================
           The Gathering Host — "terrible as an army with banners."
           Each witness carries a medallion (a sigil for now, a portrait
           later via data-portrait on the row). As his row scrolls under
           the header a gold coin joins the gathered host up top, which
           builds into an army by the end. All progressive enhancement —
           with no JS the medallions simply sit in the rows.
           ============================================================ */

        /* Per-witness medallion, in the timeline row */
        .tl-head { display: flex; align-items: center; gap: 14px; margin: 0 0 20px; }
        .tl-head .tl-name { margin: 0; }
        .medallion { flex: 0 0 auto; width: 46px; height: 46px; border-radius: 50%;
            display: grid; place-items: center; position: relative; overflow: hidden; isolation: isolate;
            border: 1px solid var(--gold-line); background: var(--gold-soft); }
        .medallion-sigil { font-family: var(--tl-serif); font-weight: 600; font-size: 16px;
            letter-spacing: 0.02em; color: var(--gold); font-feature-settings: 'tnum'; }
        .medallion-img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover;
            filter: grayscale(1) contrast(1.02); }
        /* Gold duotone: recolor any source art to one warm hue so 50 disparate
           painting styles read as a matched set of medallions. The overlay takes
           --gold's hue and chroma and the portrait's own luminance. */
        .medallion.has-img::after { content: ''; position: absolute; inset: 0;
            background: var(--gold); mix-blend-mode: color; opacity: 0.7; }
        .medallion.has-img .medallion-sigil { display: none; }

        /* Fixed muster band, docked under the sticky header */
        .host { position: fixed; left: 0; right: 0;
            top: calc(56px + env(safe-area-inset-top, 0px)); height: 46px; z-index: 30;
            display: flex; align-items: center; justify-content: center; pointer-events: none;
            opacity: 0; transition: opacity 0.45s ease;
            background: linear-gradient(180deg, var(--bg-0) 42%, transparent); }
        .host.active { opacity: 1; }
        .host-rail { display: flex; align-items: center; max-width: 900px; padding: 0 16px; }
        .coin { flex: 0 0 auto; width: 26px; height: 26px; border-radius: 50%;
            display: grid; place-items: center; position: relative; overflow: hidden; isolation: isolate;
            border: 1px solid var(--gold-line); background: var(--gold-soft);
            transition: margin-left 0.35s ease, transform 0.35s ease, opacity 0.35s ease; }
        .coin-sigil { font-family: var(--tl-serif); font-weight: 600; font-size: 10px;
            color: var(--gold); }
        .coin-img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover;
            filter: grayscale(1) brightness(1.02) contrast(1.02); }
        /* The gathered host is gold from the moment each father joins it. */
        .coin.has-img::after { content: ''; position: absolute; inset: 0;
            background: var(--gold); mix-blend-mode: color; opacity: 0.8; }
        .coin.has-img .coin-sigil { display: none; }
        .coin.enter { opacity: 0; transform: translateY(-8px) scale(0.5); }

        .tl-year { top: 112px; }  /* clear the muster band */

        @media (max-width: 600px) {
            .medallion { width: 38px; height: 38px; }
            .medallion-sigil { font-size: 14px; }
            .tl-head { gap: 10px; }
            .tl-year { top: 104px; }
        }
        @media (prefers-reduced-motion: reduce) {
            .coin, .host { transition: opacity 0.2s ease; }
            .coin.enter { transform: none; }
        }

        /* ============================================================
           EASTER EGG — "The army had faces."
           Click the gold divider-lozenge to set <body class="fire">.
           These same witnesses were also hot-tempered, strange, gloriously
           human people; the Church is terrible as an army with banners
           anyway. In fire mode a handful of rows swap their reverent quote
           for a spicier one (shade / holy zeal / genuinely weird); a holy-white
           flame band burns along the foot of the page and a small tongue of fire
           rests on each witness's medallion — Pentecost. All additive:
           the default HTML is never edited — the swap and restore happen
           in JS, so with JS off nothing changes.
           ============================================================ */

        /* The lozenge is the trigger — quietly invite the curious. */
        .divider-mark { cursor: pointer; transition: box-shadow .35s ease, transform .35s ease; }
        .divider:hover .divider-mark,
        .divider-mark:focus-visible { box-shadow: 0 0 12px var(--gold); transform: rotate(45deg) scale(1.15); outline: none; }
        body.fire .divider-mark { background: var(--gold); box-shadow: 0 0 14px var(--gold); }

        /* Swapped-quote block */
        .spicy-inject { margin: 0 0 22px; }

        /* ---- Fire mode: the demon's fear made visible ----
           The Screwtape panel gives way to Guido Reni's St Michael treading on
           the devil; the quote drops to a caption. Full colour, its edges
           vignetted into the dark so it emerges rather than sits in a box. */
        .michael { display: none; margin: 0; }
        body.fire .screwtape .pull { display: none; }
        body.fire .michael { display: block; text-align: center; }
        .michael-img { display: block; width: 100%; max-width: 380px; height: auto; margin: 0 auto;
            opacity: 0; transition: opacity 1.1s ease;
            -webkit-mask-image: radial-gradient(ellipse 74% 64% at 50% 44%, #000 52%, transparent 100%);
                    mask-image: radial-gradient(ellipse 74% 64% at 50% 44%, #000 52%, transparent 100%); }
        .michael-img.ready { opacity: 1; }
        .michael figcaption { font-family: var(--tl-serif); font-style: italic; color: var(--fg-2);
            font-size: 14px; line-height: 1.65; max-width: 520px; margin: 6px auto 0; }
        .michael figcaption em { font-style: normal; }

        /* Fire mode is a curated view: only the swapped fathers remain, and the
           "gathering host" band steps aside (it would otherwise muster the
           hidden rows). */
        body.fire .fire-hidden { display: none; }
        body.fire .host { display: none; }

        /* ---- Holy-white flame band along the foot of the page ----
           A WebGL fire shader (original, noise-driven; three.js loaded lazily on
           first light) rendered into a bottom canvas. Screen-blended so it reads
           as light over the dark. The radial .band-glow doubles as a graceful
           fallback if three.js can't load. */
        #fire-band { position: fixed; left: 0; right: 0; bottom: 0; height: 115px; z-index: 6;
            pointer-events: none; opacity: 0; transition: opacity .8s ease; mix-blend-mode: screen; }
        body.fire #fire-band { opacity: 1; }
        #fire-band .band-glow { position: absolute; left: 0; right: 0; bottom: 0; height: 65px;
            background: radial-gradient(120% 130% at 50% 140%, rgba(255,255,255,0.22),
                var(--gold-soft) 46%, transparent 74%); }
        #fire-band canvas { position: absolute; inset: 0; width: 100%; height: 100%; display: block; }

        /* ---- A pulsating nimbus of fire on each witness's medallion ----
           A gold-white ring that breathes (scale + opacity, GPU-friendly) — at
           once a saint's halo and Pentecost's tongue of fire. Pure CSS. The
           portrait keeps its round clip via its own border-radius, so the coin
           can let the nimbus glow past its edge. */
        .medallion-img, .medallion.has-img::after { border-radius: 50%; }
        body.fire .medallion { overflow: visible; box-shadow: 0 0 9px 1px var(--gold-soft); }
        body.fire .medallion::before {
            content: ''; position: absolute; inset: -3px; border-radius: 50%; z-index: 2;
            pointer-events: none; border: 1.5px solid rgba(255,240,205,0.85);
            box-shadow: 0 0 9px 2px var(--gold), 0 0 20px 5px var(--gold-soft);
            transform-origin: 50% 50%;
            animation: nimbus 2.4s ease-in-out infinite; }
        /* Desync so the gathered host doesn't pulse in unison. */
        body.fire .tl-row:nth-child(3n) .medallion::before   { animation-duration: 2.9s; animation-delay: -0.8s; }
        body.fire .tl-row:nth-child(3n+1) .medallion::before { animation-duration: 2.1s; animation-delay: -1.4s; }
        body.fire .tl-row:nth-child(4n+2) .medallion::before { animation-duration: 3.3s; animation-delay: -0.4s; }
        @keyframes nimbus {
            0%,100% { transform: scale(1.0);  opacity: 0.5; }
            50%     { transform: scale(1.16); opacity: 0.95; }
        }

        /* Extinguish control, only in fire mode */
        .fire-exit { position: fixed; right: 18px; bottom: 18px; z-index: 40;
            display: none; align-items: center; gap: 7px; cursor: pointer;
            font-family: var(--sans); font-size: 12px; letter-spacing: 0.08em;
            color: var(--fg-1); background: var(--bg-1); border: 1px solid var(--line);
            padding: 9px 14px; border-radius: 100px; opacity: 0; transition: opacity .4s ease, border-color .2s; }
        body.fire .fire-exit { display: flex; opacity: 1; }
        .fire-exit:hover { border-color: var(--gold-line); color: var(--gold); }

        @media (prefers-reduced-motion: reduce) {
            #fire-band { transition: opacity .3s ease; }   /* shader renders a single still frame (JS) */
            body.fire .medallion::before { animation: none; }   /* a still tongue of fire */
            .divider-mark, body.fire .divider-mark { transition: none; }
        }
    </style>
</head>
<body>
    <?php $current_page = 'home'; include 'nav.php'; ?>

    <main class="about-page">

        <section class="wrap intro">
            <div class="eyebrow">Historical Christian Faith</div>
            <h1>The Church, spread out through all time</h1>
            <hr class="rule">
            <p class="lead">Ask what the earliest Christians said about a single verse &mdash; or a whole doctrine &mdash; and read their own answer. Three databases lie behind this site, opened across its pages to be searched and read.</p>
            <p class="lead">A <strong><a href="/by_father.php">writings</a></strong> library of public-domain translations of the Fathers. A <strong><a href="/john/3/16">commentaries</a></strong> database, those same writings resolved down to the individual verses they touch, so you can ask what the early church said about a single line &mdash; or <a href="/random">open one at random</a>. And a <strong><a href="/doctrine/">doctrine</a></strong> database, which traces a belief across the centuries and shows you where it was argued, settled, or left open.</p>
            <p class="lead">And they are not sealed behind it. All three databases are released into the public domain and built in the open &mdash; the <strong><a href="https://github.com/HistoricalChristianFaith/Writings-Database" target="_blank" rel="noopener">writings</a></strong>, <strong><a href="https://github.com/HistoricalChristianFaith/Commentaries-Database" target="_blank" rel="noopener">commentaries</a></strong>, and <strong><a href="https://github.com/HistoricalChristianFaith/Doctrine-Database" target="_blank" rel="noopener">doctrine</a></strong> repositories &mdash; free for anyone to download, build upon, or contribute to.</p>
        </section>

        <div class="divider" role="separator" aria-hidden="true"><span class="divider-mark"></span></div>

        <section class="wrap screwtape">
            <blockquote class="pull">
                <p>&ldquo;I do not mean the Church as we see her spread out through all time and space and rooted in eternity, terrible as an army with banners. That, I confess, is a spectacle which makes even our boldest tempters uneasy. But fortunately it is quite invisible to these humans.&rdquo;</p>
                <footer>Screwtape, a demon of some seniority &mdash; C. S. Lewis, <em>The Screwtape Letters</em>, Letter I</footer>
            </blockquote>
            <!-- Fire mode: the demon's fear made visible. The panel above gives way
                 to Guido Reni's St Michael; the image is lazy-loaded on first light. -->
            <figure class="michael" aria-hidden="true">
                <img class="michael-img" alt="Saint Michael the Archangel treading on the devil — Guido Reni, c. 1636">
                <figcaption>&ldquo;&hellip;terrible as an army with banners. That, I confess, is a spectacle which makes even our boldest tempters uneasy.&rdquo; &mdash; Screwtape, in C. S. Lewis&rsquo;s <em>The Screwtape Letters</em></figcaption>
            </figure>
        </section>

        <section class="made-visible">
            <div class="eyebrow">Made visible</div>
            <div class="drop"></div>
        </section>

        <div class="timeline">
<div class="tl-era">
        <div class="tl-era-line"></div>
        <div class="tl-era-label">Before Nicaea</div>
        <div class="tl-era-line"></div>
      </div>

      <div class="tl-row" data-portrait="/portraits/clement-of-rome.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">99</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/Pope_Clement_I" target="_blank" rel="noopener">Clement of Rome</a></h2>
          <div class="tl-quote">
            <p class="tl-text">Let your children take part in the instruction that is in Christ, let them learn how powerful with God is humility, how strong is a pure love, how the fear of him is beautiful and great and saves those who live in it in holiness with a pure mind. For he is a searcher of thoughts and desires; his breath is in us, and when he wills, he will take it away.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Clement%2520of%2520Rome%2FFirst%2520Epistle%2520to%2520the%2520Corinthians.html#Let+your+children+take+part+in+the+instruction+that+is+in+Christ%2C+let+them+learn+how+powerful+with+God+is+humility%2C+how+strong+is+a+pure+love%2C+how+the+fear+of+him+is+beautiful+and+great+and+saves+those+who+live+in+it+in+holiness+with+a+pure+mind.+For+he+is+a+searcher+of+thoughts+and+desires%3B+his+breath+is+in+us%2C+and+when+he+wills%2C+he+will+take+it+away.">1 Clement 21</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">The Master, brothers, has need of nothing at all. He desires not anything of anyone, save to confess to him.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Clement%2520of%2520Rome%2FFirst%2520Epistle%2520to%2520the%2520Corinthians.html#The+Lord%2C+brethren%2C+stands+in+need+of+nothing%3B+and+He+desires+nothing+of+any+one%2C+except+that+confession+be+made+to+Him">1 Clement 52</a></div>
          </div>
        </div>
      </div>

      <div class="tl-row" data-portrait="/portraits/ignatius-of-antioch.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">108</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/Ignatius_of_Antioch" target="_blank" rel="noopener">Ignatius of Antioch</a></h2>
          <div class="tl-quote">
            <p class="tl-text">It is better for a man to be silent and be a Christian, than to talk and not to be one... It is good to teach, if he who speaks also acts. For he who shall both "do and teach, the same shall be great in the kingdom."</p>
            <div class="tl-cite"><a href="/by_father.php?file=Ignatius%2520of%2520Antioch%2FEpistle%2520to%2520the%2520Ephesians.html#It+is+better+for+a+man+to+be+silent+and+be+a+Christian%2C+than+to+talk+and+not+to+be+one...+It+is+good+to+teach%2C+if+he+who+speaks+also+acts.+For+he+who+shall+both+%22do+and+teach%2C+the+same+shall+be+great+in+the+kingdom.%22">Epistle to the Ephesians</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">Stand firm, as does an anvil which is beaten. It is the part of a noble athlete to be wounded, and yet to conquer. And especially, we ought to bear all things for the sake of God, that He also may bear with us.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Ignatius%2520of%2520Antioch%2FEpistle%2520to%2520Polycarp.html#Stand+firm%2C+as+does+an+anvil+which+is+beaten.+It+is+the+part+of+a+noble+athlete+to+be+wounded%2C+and+yet+to+conquer.+And+especially%2C+we+ought+to+bear+all+things+for+the+sake+of+God%2C+that+He+also+may+bear+with+us.">Epistle to Polycarp</a></div>
          </div>
        </div>
      </div>

      <div class="tl-row" data-portrait="/portraits/justin-martyr.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">165</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/Justin_Martyr" target="_blank" rel="noopener">Justin Martyr</a></h2>
          <div class="tl-quote">
            <p class="tl-text">God formerly gave the sun as an object of worship, as it is written, but no one ever was seen to endure death on account of his faith in the sun; but for the name of Jesus you may see men of every nation who have endured and do endure all sufferings, rather than deny Him. For the word of His truth and wisdom is more ardent and more light-giving than the rays of the sun, and sinks down into the depths of heart and mind.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Justin%2520Martyr%2FDialogue%2520with%2520Trypho.html#God+formerly+gave+the+sun+as+an+object+of+worship%2C+as+it+is+written%2C+but+no+one+ever+was+seen+to+endure+death+on+account+of+his+faith+in+the+sun%3B+but+for+the+name+of+Jesus+you+may+see+men+of+every+nation+who+have+endured+and+do+endure+all+sufferings%2C+rather+than+deny+Him.+For+the+word+of+His+truth+and+wisdom+is+more+ardent+and+more+light-giving+than+the+rays+of+the+sun%2C+and+sinks+down+into+the+depths+of+heart+and+mind.">Dialogue with Trypho 121</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">For He can be called by all those names, since He ministers to the Father's will, and since He was begotten of the Father by an act of will... and just as we see also happening in the case of a fire, which is not lessened when it has kindled another, but remains the same; and that which has been kindled by it likewise appears to exist by itself, not diminishing that from which it was kindled.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Justin%2520Martyr%2FDialogue%2520with%2520Trypho.html#For+He+can+be+called+by+all+those+names%2C+since+He+ministers+to+the+Father%27s+will%2C+and+since+He+was+begotten+of+the+Father+by+an+act+of+will...+and+just+as+we+see+also+happening+in+the+case+of+a+fire%2C+which+is+not+lessened+when+it+has+kindled+another%2C+but+remains+the+same%3B+and+that+which+has+been+kindled+by+it+likewise+appears+to+exist+by+itself%2C+not+diminishing+that+from+which+it+was+kindled.">Dialogue with Trypho 61</a></div>
          </div>
        </div>
      </div>

      <div class="tl-row" data-portrait="/portraits/irenaeus.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">202</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/Irenaeus" target="_blank" rel="noopener">Irenaeus</a></h2>
          <div class="tl-quote">
            <p class="tl-text">The sacred books acknowledge with regard to Christ, that as He is the Son of man, so is the same Being not a mere man... And as He hungered, so did He satisfy others; and as He thirsted, so did He of old cause the Jews to drink... And as He slept, so did He also rule the sea, the winds, and the storms. And as He suffered, so also is He alive, and life-giving, and healing all our infirmity. And as He died, so is He also the Resurrection of the dead... for whom a manger sufficed, yet who filled all things; who was dead, yet who liveth for ever and ever. Amen.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Irenaeus%2FFragments%2520from%2520the%2520Lost%2520Writings%2520of%2520Irenaeus.html#sacred+books+acknowledge+with+regard+to+Christ%2C+that+as+He+is+the+Son+of+man%2C+so+is+the+same+Being+not+a+%5Bmere%5D+man%3B+and+as+He+is+flesh%2C+so+is+He+also+spirit%2C+and+the+Word+of+God%2C+and+God.+And+as+He+was+born+of+Mary+in+the+last+times%2C+so+did+He+also+proceed+from+God+as+the+First-begotten+of+every+creature%3B+and+as+He+hungered%2C+so+did+He+satisfy+%5Bothers%5D%3B+and+as+He+thirsted%2C+so+did+He+of+old+cause+the+Jews+to+drink%2C+for+the+%22Rock+was+Christ%22+Himself%3A+thus+does+Jesus+now+give+to+His+believing+people+power+to+drink+spiritual+waters%2C+which+spring+up+to+life+eternal.+And+as+He+slept%2C+so+did+He+also+rule+the+sea%2C+the+winds%2C+and+the+storms.+And+as+He+suffered%2C+so+also+is+He+alive%2C+and+life-giving%2C+and+healing+all+our+infirmity.+And+as+He+died%2C+so+is+He+also+the+Resurrection+of+the+dead.+He+suffered+shame+on+earth%2C+while+He+is+higher+than+all+glory+and+praise+in+heaven%3B+for+whom+a+manger+sufficed%2C+yet+who+filled+all+things%3B+who+was+dead%2C+yet+who+liveth+for+ever+and+ever">Fragments from the Lost Writings</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">For it must be that thou, at the outset, shouldest hold the rank of a man, and then afterwards partake of the glory of God. For thou dost not make God, but God thee. If, then, thou art God's workmanship, await the hand of thy Maker which creates everything in due time... Offer to Him thy heart in a soft and tractable state, and preserve the form in which the Creator has fashioned thee, having moisture in thyself, lest, by becoming hardened, thou lose the impressions of His fingers.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Irenaeus%2FIrenaeus%2520Against%2520Heresies%2520Book%25204.html#For+it+must+be+that+thou%2C+at+the+outset%2C+shouldest+hold+the+rank+of+a+man%2C+and+then+afterwards+partake+of+the+glory+of+God.+For+thou+dost+not+make+God%2C+but+God+thee.+If%2C+then%2C+thou+art+God%27s+workmanship%2C+await+the+hand+of+thy+Maker+which+creates+everything+in+due+time...+Offer+to+Him+thy+heart+in+a+soft+and+tractable+state%2C+and+preserve+the+form+in+which+the+Creator+has+fashioned+thee%2C+having+moisture+in+thyself%2C+lest%2C+by+becoming+hardened%2C+thou+lose+the+impressions+of+His+fingers.">Against Heresies 4.39.2</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">For the glory of God is a living man; and the life of man consists in beholding God. For if the manifestation of God which is made by means of the creation, affords life to all living in the earth, much more does that revelation of the Father which comes through the Word, give life to those who see God.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Irenaeus%2FIrenaeus%2520Against%2520Heresies%2520Book%25204.html#For+the+glory+of+God+is+a+living+man%3B+and+the+life+of+man+consists+in+beholding+God.+For+if+the+manifestation+of+God+which+is+made+by+means+of+the+creation%2C+affords+life+to+all+living+in+the+earth%2C+much+more+does+that+revelation+of+the+Father+which+comes+through+the+Word%2C+give+life+to+those+who+see+God.">Against Heresies Book IV</a></div>
          </div>
        </div>
      </div>

      <div class="tl-row" data-portrait="/portraits/clement-of-alexandria.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">215</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/Clement_of_Alexandria" target="_blank" rel="noopener">Clement of Alexandria</a></h2>
          <div class="tl-quote">
            <p class="tl-text">Before the Lord's coming, philosophy was an essential guide to righteousness for the Greeks. At the present time, it is a useful guide toward reverence for God... For philosophy was to the Greek world what the Law was to the Hebrews, a tutor escorting them to Christ. So philosophy is a preparatory process; it opens the road for the person whom Christ brings to his final goal.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Clement%2520of%2520Alexandria%2FThe%2520Stromata%2520Book%25201.html#Accordingly%2C+before+the+advent+of+the+Lord%2C+philosophy+was+necessary+to+the+Greeks+for+righteousness.+And+now+it+becomes+conducive+to+piety%3B+being+a+kind+of+preparatory+training+to+those+who+attain+to+faith+through+demonstration.+%22For+thy+foot%2C%22+it+is+said%2C+%22will+not+stumble%2C+if+thou+refer+what+is+good%2C+whether+belonging+to+the+Greeks+or+to+us%2C+to+Providence.%22+For+God+is+the+cause+of+all+good+things%3B+but+of+some+primarily%2C+as+of+the+Old+and+the+New+Testament%3B+and+of+others+by+consequence%2C+as+philosophy.+Perchance%2C+too%2C+philosophy+was+given+to+the+Greeks+directly+and+primarily%2C+till+the+Lord+should+call+the+Greeks.+For+this+was+a+schoolmaster+to+bring+%22the+Hellenic+mind%2C%22+as+the+law%2C+the+Hebrews%2C+%22to+Christ.%22+Philosophy%2C+therefore%2C+was+a+preparation%2C+paving+the+way+for+him+who+is+perfected+in+Christ.">The Stromata Book 1</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">Prayer is, then, to speak more boldly, converse with God. Though whispering, consequently, and not opening the lips, we speak in silence, yet we cry inwardly. For God hears continually all the inward converse.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Clement%2520of%2520Alexandria%2FThe%2520Stromata%2520Book%25207.html#Prayer+is%2C+then%2C+to+speak+more+boldly%2C+converse+with+God.+Though+whispering%2C+consequently%2C+and+not+opening+the+lips%2C+we+speak+in+silence%2C+yet+we+cry+inwardly.+For+God+hears+continually+all+the+inward+converse.">The Stromata Book 7</a></div>
          </div>
        </div>
      </div>

      <div class="tl-row" data-portrait="/portraits/tertullian.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">220</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/Tertullian" target="_blank" rel="noopener">Tertullian</a></h2>
          <div class="tl-quote">
            <p class="tl-text">The Son of God was crucified; I am not ashamed because men must needs be ashamed of it. And the Son of God died; it is by all means to be believed, because it is absurd. And He was buried, and rose again; the fact is certain, because it is impossible.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Tertullian%2FOn%2520the%2520Flesh%2520of%2520Christ.html#The+Son+of+God+was+crucified%3B+I+am+not+ashamed+because+men+must+needs+be+ashamed+of+it.+And+the+Son+of+God+died%3B+it+is+by+all+means+to+be+believed%2C+because+it+is+absurd.+And+He+was+buried%2C+and+rose+again%3B+the+fact+is+certain%2C+because+it+is+impossible.">On the Flesh of Christ</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">Render to Caesar what are Caesar's, and what are God's to God; that is, the image of Caesar, which is on the coin, to Caesar, and the image of God, which is on man, to God; so as to render to Caesar indeed money, to God yourself.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Tertullian%2FOn%2520Idolatry.html#Render+to+Caesar+what+are+Caesar%27s%2C+and+what+are+God%27s+to+God%3B+that+is%2C+the+image+of+Caesar%2C+which+is+on+the+coin%2C+to+Caesar%2C+and+the+image+of+God%2C+which+is+on+man%2C+to+God%3B+so+as+to+render+to+Caesar+indeed+money%2C+to+God+yourself.">On Idolatry</a></div>
          </div>
        </div>
      </div>

      <div class="tl-row" data-portrait="/portraits/hippolytus-of-rome.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">235</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/Hippolytus_of_Rome" target="_blank" rel="noopener">Hippolytus of Rome</a></h2>
          <div class="tl-quote">
            <p class="tl-text">When he came into the world, He was manifested as God and man. And it is easy to perceive the man in Him, when He hungers and shows exhaustion, and is weary and thirsty, and withdraws in fear, and is in prayer and in grief, and sleeps on a boat's pillow... and with a cry commits His spirit to His Father, and drops His head and gives up the ghost... and is raised by the Father on the third day. And the divine in Him, on the other hand, is equally manifest, when He is worshipped by angels, and seen by shepherds, and waited for by Simeon... and at a marriage makes wine of water, and chides the sea when tossed by the violence of winds, and walks upon the deep, and makes one see who was blind from birth, and raises Lazarus when dead for four days, and works many wonders, and forgives sins, and grants power to His disciples.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Hippolytus%2520of%2520Rome%2FFragments%2520-%2520Exegetical.%2520Fragments%2520from%2520Commentaries%2520on%2520Various%2520Books%2520of%2520Scripture.html#When+he+came+into+the+world%2C+He+was+manifested+as+God+and+man.+And+it+is+easy+to+perceive+the+man+in+Him%2C+when+He+hungers+and+shows+exhaustion%2C+and+is+weary+and+thirsty%2C+and+withdraws+in+fear%2C+and+is+in+prayer+and+in+grief%2C+and+sleeps+on+a+boat%27s+pillow...+and+with+a+cry+commits+His+spirit+to+His+Father%2C+and+drops+His+head+and+gives+up+the+ghost...+and+is+raised+by+the+Father+on+the+third+day.+And+the+divine+in+Him%2C+on+the+other+hand%2C+is+equally+manifest%2C+when+He+is+worshipped+by+angels%2C+and+seen+by+shepherds%2C+and+waited+for+by+Simeon...+and+at+a+marriage+makes+wine+of+water%2C+and+chides+the+sea+when+tossed+by+the+violence+of+winds%2C+and+walks+upon+the+deep%2C+and+makes+one+see+who+was+blind+from+birth%2C+and+raises+Lazarus+when+dead+for+four+days%2C+and+works+many+wonders%2C+and+forgives+sins%2C+and+grants+power+to+His+disciples.">Fragments, On Psalm II</a></div>
          </div>
        </div>
      </div>

      <div class="tl-row" data-portrait="/portraits/origen-of-alexandria.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">253</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/Origen" target="_blank" rel="noopener">Origen of Alexandria</a></h2>
          <div class="tl-quote">
            <p class="tl-text">"God is spirit, and those who worship him should worship in spirit and in truth." Our God is also "a consuming fire." Therefore God is called by two names: "spirit" and "fire." To the just he is spirit; to sinners he is fire.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Origen%2520of%2520Alexandria%2FHomilies%2520on%2520Luke%2FHomily26.html#God+is+a+spirit%2C+and+those+who+worship+him+must+worship+in+spirit+and+in+truth%22.+Our+God+too+%22is+a+consuming+fire%22.+God%2C+therefore%2C+is+called+by+two+names%3A+both+%22spirit%22+and+%22fire%22%3A+spirit+for+the+righteous%2C+fire+for+sinners">Homilies on Luke 26.1</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">...the entire life of the saint taken as a whole is a single great prayer. What is customarily called prayer is, then, a part of this prayer.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Origen%2520of%2520Alexandria%2FOn%2520Prayer%2FOn%2520Prayer.html#we+may+speak+of+the+whole+life+of+a+saint+as+one+great+continuous+prayer.+Of+such+prayer+what+is+usually+termed+prayer+is+indeed+a+part">On Prayer 12.2</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">Sometimes it does not turn out to be an advantage for one to be healed quickly or superficially, especially if the disease by this means becomes even more shut up in the internal organs where it rages more fiercely. Therefore God, who perceives secret things and who knows all things before they come to be, in his great goodness delays the healing of such persons and defers the remedy to a later time. If I may speak paradoxically, God heals them by not healing them, lest a premature recovery of health should render them incurable.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Origen%2520of%2520Alexandria%2FDe%2520Principiis%2FBook%25203.html#sometimes+it+does+not+lead+to+good+results+for+a+man+to+be+cured+too+quickly%2C+especially+if+the+disease%2C+being+shut+up+within+the+inner+parts+of+the+body%2C+rage+with+greater+fierceness.+Whence+God%2C+who+is+acquainted+with+secret+things%2C+and+knows+all+things+before+they+happen%2C+in+His+great+goodness+delays+the+cure+of+such%2C+and+postpones+their+recovery+to+a+remoter+period%2C+and%2C+so+to+speak%2C+cures+them+by+not+curing+them%2C+lest+a+too+favourable+state+of+health">On First Principles 3.1.7</a></div>
          </div>
        </div>
      </div>

      <div class="tl-row" data-portrait="/portraits/cyprian.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">258</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/Cyprian" target="_blank" rel="noopener">Cyprian</a></h2>
          <div class="tl-quote">
            <p class="tl-text">The kingdom of God, beloved brethren, is beginning to be at hand; the reward of life, and the rejoicing of eternal salvation... are now coming, with the passing away of the world... What room is there here for anxiety and solicitude? Who, in the midst of these things, is trembling and sad, except he who is without hope and faith? For it is for him to fear death who is not willing to go to Christ. It is for him to be unwilling to go to Christ who does not believe that he is about to reign with Christ.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Cyprian%2FTreatise%25207.%2520On%2520the%2520Mortality.html#The+kingdom+of+God%2C+beloved+brethren%2C+is+beginning+to+be+at+hand%3B+the+reward+of+life%2C+and+the+rejoicing+of+eternal+salvation...+are+now+coming%2C+with+the+passing+away+of+the+world...+What+room+is+there+here+for+anxiety+and+solicitude%3F+Who%2C+in+the+midst+of+these+things%2C+is+trembling+and+sad%2C+except+he+who+is+without+hope+and+faith%3F+For+it+is+for+him+to+fear+death+who+is+not+willing+to+go+to+Christ.+It+is+for+him+to+be+unwilling+to+go+to+Christ+who+does+not+believe+that+he+is+about+to+reign+with+Christ.">On the Mortality</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">But what carelessness it is, to be distracted and carried away by foolish and profane thoughts when you are praying to the Lord, as if there were anything which you should rather be thinking of than that you are speaking with God! How can you ask to be heard of God, when you yourself do not hear yourself? Do you wish that God should remember you when you ask, if you yourself do not remember yourself?</p>
            <div class="tl-cite"><a href="/by_father.php?file=Cyprian%2FTreatise%25204.%2520On%2520the%2520Lord%2527s%2520Prayer.html#But+what+carelessness+it+is%2C+to+be+distracted+and+carried+away+by+foolish+and+profane+thoughts+when+you+are+praying+to+the+Lord%2C+as+if+there+were+anything+which+you+should+rather+be+thinking+of+than+that+you+are+speaking+with+God%21+How+can+you+ask+to+be+heard+of+God%2C+when+you+yourself+do+not+hear+yourself%3F+Do+you+wish+that+God+should+remember+you+when+you+ask%2C+if+you+yourself+do+not+remember+yourself%3F">On the Lord's Prayer</a></div>
          </div>
        </div>
      </div>

      <div class="tl-row" data-portrait="/portraits/methodius-of-olympus.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">311</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/Methodius_of_Olympus" target="_blank" rel="noopener">Methodius of Olympus</a></h2>
          <div class="tl-quote">
            <p class="tl-text">It is not in our power to think or not to think of improper things, but to act or not to act upon our thoughts. For we cannot hinder thoughts from coming into our minds, since we receive them when they are inspired into us from without; but we are able to abstain from obeying them and acting upon them.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Methodius%2520of%2520Olympus%2FFrom%2520the%2520Discourse%2520on%2520the%2520Resurrection.html#It+is+not+in+our+power+to+think+or+not+to+think+of+improper+things%2C+but+to+act+or+not+to+act+upon+our+thoughts.+For+we+cannot+hinder+thoughts+from+coming+into+our+minds%2C+since+we+receive+them+when+they+are+inspired+into+us+from+without%3B+but+we+are+able+to+abstain+from+obeying+them+and+acting+upon+them.">On the Resurrection</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">Think of a skilled painter painting a likeness of himself on a surface. So we may now imitate the same characteristics that God himself has displayed in his becoming a human being... His purpose in consenting to put on human flesh when he was God was this: that we, upon seeing the divine image in this tablet, so to speak, might imitate this incomparable artist.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Methodius%2520of%2520Olympus%2FDiscourse%25201.-Marcella.html#For+it+is+then+that+we+are+truly+fashioned+in+the+likeness+of+God%2C+when+we+represent+His+features+in+a+human+life%2C+like+skilful+painters%2C+stamping+them+upon+ourselves+as+upon+tablets%2C+learning+the+path+which+He+showed+us.+And+for+this+reason+He%2C+being+God%2C+was+pleased+to+put+on+human+flesh%2C+so+that+we%2C+beholding+as+on+a+tablet+the+divine+Pattern+of+our+life%2C+should+also+be+able+to+imitate+Him+who+painted+it.">Symposium 1.4</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">As soon as souls have left this world... they see wonderful and glorious and blessed things of beauty... They see there righteousness, prudence, love, truth, temperance and other flowers and plants of wisdom... We see here only the shadows and apparitions of them, as in dreams... But there, in him whose name is I AM, they are seen perfect and clear as they are.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Methodius%2520of%2520Olympus%2FDiscourse%25208.-Thekla.html#they+see+wonderful+and+glorious+and+blessed+things+of+beauty%2C+and+such+as+cannot+be+spoken+to+men.+They+see+there+righteousness+itself+and+prudence%2C+and+love+itself%2C+and+truth+and+temperance%2C+and+other+flowers+and+plants+of+wisdom%2C+equally+splendid%2C+of+which+we+here+behold+only+the+shadows+and+apparitions%2C+as+in+dreams%2C+and+think+that+they+consist+of+the+actions+of+men%2C+because+there+is+no+clear+image+of+them+here%2C+but+only+dim+copies%2C+which+themselves+we+see+often+when+making+dark+copies+of+them.+For+never+has+any+one+seen+with+his+eyes+the+greatness+or+the+form+or+the+beauty+of+righteousness+itself%2C+or+of+understanding%2C+or+of+peace%3B+but+there%2C+in+Him+whose+name+is+I+AM%2C+they+are+seen+perfect+and+clear%2C+as+they+are.">Symposium 8.2-3</a></div>
          </div>
        </div>
      </div>

      <div class="tl-era">
        <div class="tl-era-line"></div>
        <div class="tl-era-label">The Nicene century and after</div>
        <div class="tl-era-line"></div>
      </div>

      <div class="tl-row" data-portrait="/portraits/lactantius.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">325</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/Lactantius" target="_blank" rel="noopener">Lactantius</a></h2>
          <div class="tl-quote">
            <p class="tl-text">It is my wish not to sin, but I am overpowered; for I am clothed with frail and weak flesh: it is this which covets, which is angry, which fears pain and death. And thus I am led on against my will; and I sin, not because it is my wish, but because I am compelled... What will that teacher of righteousness say in reply to these things?... unless he himself also shall be clothed with flesh, so that he may show that even the flesh is capable of virtue.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Lucius%2520Caecilius%2520Firmianus%2520Lactantius%2FThe%2520Divine%2520Institutes%2520Book%25204.html#It+is+my+wish+not+to+sin%2C+but+I+am+overpowered%3B+for+I+am+clothed+with+frail+and+weak+flesh%3A+it+is+this+which+covets%2C+which+is+angry%2C+which+fears+pain+and+death.+And+thus+I+am+led+on+against+my+will%3B+and+I+sin%2C+not+because+it+is+my+wish%2C+but+because+I+am+compelled...+What+will+that+teacher+of+righteousness+say+in+reply+to+these+things%3F...+unless+he+himself+also+shall+be+clothed+with+flesh%2C+so+that+he+may+show+that+even+the+flesh+is+capable+of+virtue.">Divine Institutes 4.24</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">Truly religion is the cultivation of the truth, but superstition of that which is false. And it makes the entire difference what you worship, not how you worship, or what prayer you offer.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Lucius%2520Caecilius%2520Firmianus%2520Lactantius%2FThe%2520Divine%2520Institutes%2520Book%25204.html#Truly+religion+is+the+cultivation+of+the+truth%2C+but+superstition+of+that+which+is+false.+And+it+makes+the+entire+difference+what+you+worship%2C+not+how+you+worship%2C+or+what+prayer+you+offer.">Divine Institutes Book 4</a></div>
          </div>
        </div>
      </div>

      <div class="tl-row" data-portrait="/portraits/eusebius-of-caesarea.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">339</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/Eusebius" target="_blank" rel="noopener">Eusebius of Caesarea</a></h2>
          <div class="tl-quote">
            <p class="tl-text">He cried out with a loud voice to the Father, "I commend my spirit" and freely departed from the body. He did not wait for death, which was lagging behind as it were in fear to come to him. Instead, he pursued it from behind and drove it on and trampled it under his feet as it was fleeing. He burst the eternal gates of death's dark realms and made a road of return back again to life for the dead bound there with the bonds of death.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Eusebius%2520of%2520Caesarea%2FThe%2520Proof%2520of%2520the%2520Gospel%2FBook%25204.html#He+cried+out+with+a+loud+voice+to+the+Father%2C+%22I+commend+my+spirit%22+and+freely+departed+from+the+body.+He+did+not+wait+for+death%2C+which+was+lagging+behind+as+it+were+in+fear+to+come+to+him.+Instead%2C+he+pursued+it+from+behind+and+drove+it+on+and+trampled+it+under+his+feet+as+it+was+fleeing.+He+burst+the+eternal+gates+of+death%27s+dark+realms+and+made+a+road+of+return+back+again+to+life+for+the+dead+bound+there+with+the+bonds+of+death.">Proof of the Gospel 4.12.3</a></div>
          </div>
        </div>
      </div>

      <div class="tl-row" data-portrait="/portraits/hilary-of-poitiers.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">367</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/Hilary_of_Poitiers" target="_blank" rel="noopener">Hilary of Poitiers</a></h2>
          <div class="tl-quote">
            <p class="tl-text">But the Word was made flesh in order that the flesh might begin to be what the Word is.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Hilary%2520of%2520Poitiers%2FOn%2520the%2520Councils%252C%2520or%2520the%2520Faith%2520of%2520the%2520Easterns.html#But+the+Word+was+made+flesh+in+order+that+the+flesh+might+begin+to+be+what+the+Word+is.">On the Councils 48</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">For he is the best student who does not read his thoughts into the book, but lets it reveal its own; who draws from it its sense, and does not import his own into it, nor force upon its words a meaning which he had determined was the right one before he opened its pages.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Hilary%2520of%2520Poitiers%2FOn%2520the%2520Trinity%2FBook%25201.html#For+he+is+the+best+student+who+does+not+read+his+thoughts+into+the+book%2C+but+lets+it+reveal+its+own%3B+who+draws+from+it+its+sense%2C+and+does+not+import+his+own+into+it%2C+nor+force+upon+its+words+a+meaning+which+he+had+determined+was+the+right+one+before+he+opened+its+pages.">On the Trinity 1.18</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">For many are kept within the pale of the church by the fear of God; yet they are tempted all the while to worldly faults by the allurements of the world. They pray, because they are afraid; they sin, because it is their will. The fair hope of future life makes them call themselves Christians; the allurements of present pleasure make them act like heathen.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Hilary%2520of%2520Poitiers%2FHomilies%2520on%2520the%2520Psalms%2FPsalm%25201.html#For+many+are+kept+within+the+pale+of+the+church+by+the+fear+of+God%3B+yet+they+are+tempted+all+the+while+to+worldly+faults+by+the+allurements+of+the+world.+They+pray%2C+because+they+are+afraid%3B+they+sin%2C+because+it+is+their+will.+The+fair+hope+of+future+life+makes+them+call+themselves+Christians%3B+the+allurements+of+present+pleasure+make+them+act+like+heathen.">Homily on Psalm 1</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">If a fool's heart is in his mouth, it is because he does not say what he has thought but thinks afterwards about what he has said.</p>
            <div class="tl-cite">Homilies on the Psalms 51.7</div>
          </div>
        </div>
      </div>

      <div class="tl-row" data-portrait="/portraits/ephrem-the-syrian.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">373</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/Ephrem_the_Syrian" target="_blank" rel="noopener">Ephrem the Syrian</a></h2>
          <div class="tl-quote">
            <p class="tl-text">But the Godhead concealed itself in the manhood and fought against Death, Death slew and was slain... And when Death came to feed after his custom, the Life in His turn swallowed up Death. This is the food that hungered to eat its eater... This is the Son of the carpenter, Who skilfully made His cross a bridge over Sheol that swallows up all, and brought over mankind into the dwelling of life... Glory be to You, Who laid Your cross as a bridge over death, that souls might pass over upon it from the dwelling of the dead to the dwelling of life!</p>
            <div class="tl-cite"><a href="/by_father.php?file=Ephrem%2520the%2520Syrian%2FHomily%2520-%2520On%2520Our%2520Lord.html#But+the+Godhead+concealed+itself+in+the+manhood+and+fought+against+Death%2C+Death+slew+and+was+slain...+And+when+Death+came+to+feed+after+his+custom%2C+the+Life+in+His+turn+swallowed+up+Death.+This+is+the+food+that+hungered+to+eat+its+eater...+This+is+the+Son+of+the+carpenter%2C+Who+skilfully+made+His+cross+a+bridge+over+Sheol+that+swallows+up+all%2C+and+brought+over+mankind+into+the+dwelling+of+life...+Glory+be+to+You%2C+Who+laid+Your+cross+as+a+bridge+over+death%2C+that+souls+might+pass+over+upon+it+from+the+dwelling+of+the+dead+to+the+dwelling+of+life%21">Homily on Our Lord</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">The Lord who is beyond measure / measures out nourishment to all, / adapting to our eyes the sight of himself, / to our hearing his voice, / His blessing to our appetite, / His wisdom to our tongue.</p>
            <div class="tl-cite">Hymns on Paradise 9.27</div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">That which people effect and transform in ten months with toil, his ten fingers effected in an instant. For he placed his hands beneath the bread as though it were earth, and spoke over it as though thunder. The murmur of his lips sprinkled over it like rain, and the breath of his mouth was there in place of the sun. Thus did he complete in the flash of one tiny moment something which requires a whole lengthy hour.</p>
            <div class="tl-cite">Commentary on the Diatessaron</div>
          </div>
        </div>
      </div>

      <div class="tl-row" data-portrait="/portraits/athanasius-of-alexandria.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">373</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/Athanasius_of_Alexandria" target="_blank" rel="noopener">Athanasius of Alexandria</a></h2>
          <div class="tl-quote">
            <p class="tl-text">He was made man that we might be made god. He manifested himself by a body that we might receive a conception of the unseen Father. He endured the hubris of humanity that we might inherit incorruptibility.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Athanasius%2520of%2520Alexandria%2FOn%2520the%2520Incarnation%2520of%2520the%2520Word.html#He+was+made+man+that+we+might+be+made+god.+He+manifested+himself+by+a+body+that+we+might+receive+a+conception+of+the+unseen+Father.+He+endured+the+hubris+of+humanity+that+we+might+inherit+incorruptibility.">On the Incarnation 54.3</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">For man is by nature afraid of death and of the dissolution of the body. But there is this most startling fact, that he who has put on the faith of the cross despises even what is naturally fearful and for Christ's sake is not afraid of death.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Athanasius%2520of%2520Alexandria%2FOn%2520the%2520Incarnation%2520of%2520the%2520Word.html#man+is+by+nature+afraid+of+death+and+of+the+dissolution+of+the+body%3B+but+there+is+this+most+startling+fact%2C+that+he+who+has+put+on+the+faith+of+the+Cross+despises+even+what+is+naturally+fearful">On the Incarnation 28</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">Not even then ought we, the faithful, to fear his appearance or give heed to his words... like a dragon he was drawn with a hook by the Saviour, and as a beast of burden he received the halter round his nostrils, and as a runaway his nostrils were bound with a ring... And he was bound by the Lord as a sparrow, that we should mock him. And with him are placed the demons his fellows, like serpents and scorpions to be trodden underfoot by us Christians.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Athanasius%2520of%2520Alexandria%2FLife%2520of%2520Antony.html#Not+even+then+ought+we%2C+the+faithful%2C+to+fear+his+appearance+or+give+heed+to+his+words...+like+a+dragon+he+was+drawn+with+a+hook+by+the+Saviour%2C+and+as+a+beast+of+burden+he+received+the+halter+round+his+nostrils%2C+and+as+a+runaway+his+nostrils+were+bound+with+a+ring...+And+he+was+bound+by+the+Lord+as+a+sparrow%2C+that+we+should+mock+him.+And+with+him+are+placed+the+demons+his+fellows%2C+like+serpents+and+scorpions+to+be+trodden+underfoot+by+us+Christians.">Life of Antony 24</a></div>
          </div>
        </div>
      </div>

      <div class="tl-row" data-portrait="/portraits/basil-of-caesarea.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">379</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/Basil_of_Caesarea" target="_blank" rel="noopener">Basil of Caesarea</a></h2>
          <div class="tl-quote">
            <p class="tl-text">There is still time for patience, time for forbearance, time for healing, time for amendment. Have you slipped? Rise up. Have you sinned? Cease. Do not stand in the way of sinners, but turn aside; for then you will be saved when turning back you bewail your sins. In fact, from labors there is health; for sweat, salvation.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Basil%2520of%2520Caesarea%2FLetters%2FLetter%252044.html#There+is+a+time+of+endurance%2C+a+time+of+long+suffering%2C+a+time+of+healing%2C+a+time+of+correction.+Have+you+stumbled%3F+Arise.+Have+you+sinned%3F+Cease.+Do+not+stand+in+the+way+of+sinners%2C+but+spring+away.+When+you+are+converted+and+groan+you+shall+be+saved.+Out+of+labour+comes+health%2C+out+of+sweat+salvation.">Letter 44</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">How can the Godhead be in the flesh? In the same way as fire can be in iron: not by moving from place to place but by the one imparting to the other its own properties. Fire does not speed toward iron, but without itself undergoing any change it causes the iron to share in its own natural attributes... The reason God is in the flesh is to kill the death that lurks there... And as ice formed on water covers its surface as long as night and darkness last but melts under the warmth of the sun, so death reigned until the coming of Christ.</p>
            <div class="tl-cite">Homily on Christ's Ancestry 2.6</div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">Take heed that you do not make fasting to consist only in abstinence from meats. True fasting is to refrain from vice. Shred to pieces all your unjust contracts. Pardon your neighbors. Forgive them their trespasses.</p>
            <div class="tl-cite">Homily 1, On Fasting</div>
          </div>
        </div>
      </div>

      <div class="tl-row" data-portrait="/portraits/ambrosiaster.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">384</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/Ambrosiaster" target="_blank" rel="noopener">Ambrosiaster</a></h2>
          <div class="tl-quote">
            <p class="tl-text">If Christ gave himself up to death at the right time for those who were unbelievers and enemies of God... how much more will he protect us with his help if we believe in him! He died for us in order to obtain life and glory for us. So if he died for his enemies, just think what he will do for his friends!</p>
            <div class="tl-cite"><a href="/by_father.php?file=Ambrosiaster%2FCommentary%2520on%2520Romans.html#Why+then+did+Christ%2C+while+we+were+still+sinners%2C+die+for+the+ungodly+according+to+the+appointed+time%3F+For+scarcely+for+a+just+man+will+one+die%3B+yet+perhaps+for+a+good+man+someone+would+even+dare+to+die.+But+God+demonstrates+His+own+love+toward+us%2C+in+that+while+we+were+still+sinners%2C+Christ+died+for+us.+Much+more+then%2C+having+now+been+justified+by+His+blood%2C+we+shall+be+saved+from+wrath+through+Him.+For+if+when+we+were+enemies+we+were+reconciled+to+God+through+the+death+of+His+Son%2C+much+more%2C+having+been+reconciled%2C+we+shall+be+saved+by+His+life.+And+not+only+that%2C+but+we+also+rejoice+in+God+through+our+Lord+Jesus+Christ%2C+through+whom+we+have+now+received+the+reconciliation.+Therefore%2C+if+he+died+for+his+enemies%2C+it+must+be+understood+how+much+he+excels+for+his+friends">Commentary on Paul's Epistles (Romans 5:6)</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">For although God sees everything and we know that everything is known to him, because we do not see ourselves being seen by him, we act differently.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Ambrosiaster%2FCommentary%2520on%2520Colossians.html#For+although+God+sees+everything+and+we+know+that+everything+is+known+to+him%2C+because+we+do+not+see+ourselves+being+seen+by+him%2C+we+act+differently.">Commentary on Colossians</a></div>
          </div>
        </div>
      </div>

      <div class="tl-row" data-portrait="/portraits/cyril-of-jerusalem.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">386</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/Cyril_of_Jerusalem" target="_blank" rel="noopener">Cyril of Jerusalem</a></h2>
          <div class="tl-quote">
            <p class="tl-text">The sum of your sins does not surpass the magnitude of God's mercies. Your wounds are not beyond the healing skill of the great Physician. Only surrender to him with faith, tell the Physician of your malady. Repeat the words of David: "I said, I will confess against myself my iniquity to the Lord," and in like manner will be verified the second part of the verse: "And you forgave the wickedness of my heart."</p>
            <div class="tl-cite"><a href="/by_father.php?file=Cyril%2520of%2520Jerusalem%2FCATECHETICAL%2520LECTURES%2FLecture%25202.html#Thine+accumulated+offences+surpass+not+the+multitude+of+God%27s+mercies%3A+thy+wounds+surpass+not+the+great+Physician%27s+skill.+Only+give+thyself+up+in+faith%3A+tell+the+Physician+thine+ailment%3A+say+thou+also%2C+like+David%3A+I+said%2C+I+will+confess+me+my+sin+unto+the+Lord%3A+and+the+same+shall+be+done+in+thy+case%2C+which+he+says+forthwith%3A+And+thou+forgavest+the+wickedness+of+my+heart">Catechetical Lecture 2.6</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">It is not only among those of us who bear the name of Christ that the dignity of faith is great. Rather, all things that are accomplished in the world, even by those who are strangers to the church, are accomplished by faith... By faith seafaring men, trusting to the thinnest plank, exchange that most solid element, the land, for the restless motion of the waves... carrying with them a faith more sure than any anchor. By faith therefore most of men's affairs are held together.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Cyril%2520of%2520Jerusalem%2FCATECHETICAL%2520LECTURES%2FLecture%25205.html#Nor+is+it+only+among+us%2C+who+bear+the+name+of+Christ%2C+that+the+dignity+of+faith+is+great9+%3A+but+likewise+all+things+that+are+accomplished+in+the+world%2C+even+by+those+who+are+aliens10+from+the+Church%2C+are+accomplished+by+faith.+By+faith+the+laws+of+marriage+yoke+together+those+who+have+lived+as+strangers%3A+and+because+of+the+faith+in+marriage+contracts+a+stranger+is+made+partner+of+a+stranger%27s+person+and+possessions.+By+faith+husbandry+also+is+sustained%2C+for+he+who+believes+not+that+he+shall+receive+a+harvest+endures+not+the+toils.+By+faith+sea-faring+men%2C+trusting+to+the+thinnest+plank%2C+exchange+that+most+solid+element%2C+the+land%2C+for+the+restless+motion+of+the+waves%2C+committing+themselves+to+uncertain+hopes%2C+and+carrying+with+them+a+faith+more+sure+than+any+anchor.+By+faith+therefore+most+of+men%27s+affairs+are+held+together">Catechetical Lecture 5.3</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">Often a person for Christ's sake is treated with contumely and unjustly dishonored; martyrdom is at hand, tortures on every side, fire, swords, wild beasts and the abyss; but the Holy Spirit gently whispers, "Wait for the Lord," for your present sufferings are slight, while your rewards will be great; endure for a little while, and you will be with the angels forever.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Cyril%2520of%2520Jerusalem%2FCATECHETICAL%2520LECTURES%2FLecture%252016.html#Oftentimes+a+man+for+Christ%27s+sake+has+been+outraged+and+dishonoured+unjustly%3B+martyrdom+is+at+hand%3B+tortures+on+every+side%2C+and+fire%2C+and+sword%2C+and+savage+beasts%2C+and+the+pit.+But+the+Holy+Ghost+softly+whispers+to+him%2C+%22Wait+thou+on+the+Lord70+%2C+O+man%3B+what+is+now+befalling+thee+is+a+small+matter%2C+the+reward+will+be+great.+Suffer+a+little+while%2C+and+thou+shale+be+with+Angels+for+ever">Catechetical Lecture 16.20</a></div>
          </div>
        </div>
      </div>

      <div class="tl-row" data-portrait="/portraits/gregory-of-nazianzus.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">390</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/Gregory_of_Nazianzus" target="_blank" rel="noopener">Gregory of Nazianzus</a></h2>
          <div class="tl-quote">
            <p class="tl-text">Such, brethren, is our life, we whose existence is so transitory. Such is the game we play upon earth. We do not exist, and then we are born, and being born we are soon dissolved. We are a fleeting dream, an apparition without substance, the flight of a bird that passes, a ship that leaves no trace upon the sea. We are dust, a vapor, the morning dew, a flower growing but a moment and withering in a moment.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Gregory%2520of%2520Nazianzus%2FOrations%2FOration%25207.html#Such%2C+my+brethren%2C+is+our+existence%2C+who+live+this+transient+life%2C+such+our+pastime+upon+earth%3A+we+come+into+existence+out+of+non-existence%2C+and+after+existing+are+dissolved.+We+are+unsubstantial+dreams%2C+impalpable+visions%2C+like+the+flight+of+a+passing+bird%2C+like+a+ship+leaving+no+track+upon+the+sea%2C+a+speck+of+dust%2C+a+vapour%2C+an+early+dew%2C+a+flower+that+quickly+blooms%2C+and+quickly+fades">Oration 7, On His Brother St. Caesarius</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">He prays, but he hears prayer. He weeps, but he causes tears to cease. He asks where Lazarus was laid, for he was man; but he raises Lazarus, for he was God... As a lamb he is silent, yet he is the Word... He is lifted up and nailed to the tree, but by the tree of life he restores us... He dies, but he gives life, and by his death, he destroys death. He is buried, but he rises again; he goes down into hell, but he brings up the souls.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Gregory%2520of%2520Nazianzus%2FOrations%2FOration%252029.html#He+prays%2C+but+he+hears+prayer.+He+weeps%2C+but+he+causes+tears+to+cease.+He+asks+where+Lazarus+was+laid%2C+for+he+was+man%3B+but+he+raises+Lazarus%2C+for+he+was+God...+As+a+lamb+he+is+silent%2C+yet+he+is+the+Word...+He+is+lifted+up+and+nailed+to+the+tree%2C+but+by+the+tree+of+life+he+restores+us...+He+dies%2C+but+he+gives+life%2C+and+by+his+death%2C+he+destroys+death.+He+is+buried%2C+but+he+rises+again%3B+he+goes+down+into+hell%2C+but+he+brings+up+the+souls.">Theological Oration 3.20</a></div>
          </div>
        </div>
      </div>

      <div class="tl-row" data-portrait="/portraits/gregory-of-nyssa.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">395</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/Gregory_of_Nyssa" target="_blank" rel="noopener">Gregory of Nyssa</a></h2>
          <div class="tl-quote">
            <p class="tl-text">Know to what extent the Creator has honoured you above all the rest of creation. The sky is not an image of God, nor is the moon, nor the sun, nor the beauty of the stars... You alone have been made the image of the Reality that transcends all understanding, the likeness of imperishable beauty... And although He is so great and holds all creation in the palm of His hand, you are able to hold Him, He dwells in you and moves within you without constraint.</p>
            <div class="tl-cite">Homilies on the Song of Songs 2</div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">In this world I have discovered the two affirmations that man is nothing and that man is great. If you consider nature alone, he is nothing and has no value; but if you regard the honor with which he has been treated, man is something great.</p>
            <div class="tl-cite">On the Origin of Man</div>
          </div>
        </div>
      </div>

      <div class="tl-row" data-portrait="/portraits/ambrose-of-milan.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">397</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/Ambrose" target="_blank" rel="noopener">Ambrose of Milan</a></h2>
          <div class="tl-quote">
            <p class="tl-text">In Christ we possess everything... Christ is all things to us. If you desire to heal your wounds, he is your doctor; if you are on fire with fever, he is your fountain; if you are burdened with iniquity, he is your justification; if you need help, he is your strength; if you fear death, he is your life; if you desire heaven, he is your way; if you are fleeing from darkness, he is your light; if you are seeking food, he is your nourishment.</p>
            <div class="tl-cite">Concerning Virginity 16.99</div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">The church has gold, not stored up but to lay out and to spend on those who need. What necessity is there to guard what is of no good?... Why are so many captives brought to the slave market, and why are so many unredeemed left to be slain by the enemy? It had been better to preserve living vessels than gold ones.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Ambrose%2520of%2520Milan%2FON%2520THE%2520DUTIES%2520OF%2520THE%2520CLERGY%2FBook%25202.html#The+church+has+gold%2C+not+stored+up+but+to+lay+out+and+to+spend+on+those+who+need.+What+necessity+is+there+to+guard+what+is+of+no+good%3F...+Why+are+so+many+captives+brought+to+the+slave+market%2C+and+why+are+so+many+unredeemed+left+to+be+slain+by+the+enemy%3F+It+had+been+better+to+preserve+living+vessels+than+gold+ones.">On the Duties of the Clergy 2.28.137</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">Do not fear that perhaps he will not receive you, for the Lord has no pleasure in the destruction of the living. Already meeting you on the way, he falls on your neck... You still dread harshness, but he has restored dignity. You are terrified of punishment, but he offers a kiss. You fear reproach, but he prepares a banquet.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Ambrose%2520of%2520Milan%2FCommentary%2520on%2520Luke.html#And+do+not+be+afraid+that+he+will+not+receive+you%3A+For+God+is+not+pleased+with+the+destruction+of+the+living+%28Wisdom+1%3A13%29%3A+even+when+he+meets+you+coming%2C+he+will+fall+upon+your+neck%3A+For+the+Lord+raises+up+the+fallen+%28Psalm+145%3A8%29.+He+will+give+a+kiss+which+is+a+pledge+of+affection+and+love.+He+will+command+that+a+stole%2C+a+ring%2C+and+shoes+be+brought+forth.+You+still+fear+injury%2C+he+restores+dignity%3A+you+fear+punishment%2C+he+offers+a+kiss%3A+you+dread+reproach%2C+he+adorns+a+feast">Exposition of the Gospel of Luke</a></div>
          </div>
        </div>
      </div>

      <div class="tl-row" data-portrait="/portraits/didymus-the-blind.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">398</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/Didymus_the_Blind" target="_blank" rel="noopener">Didymus the Blind</a></h2>
          <div class="tl-quote">
            <p class="tl-text">God has made creation so that human beings, through an outward picture of the greatness and beauty of created things, might understand that God exists... When you see a ship which is piloted and holds its course, you perceive the idea of a helmsman even if he is not visible. And if you see a chariot which travels orderly, you get the idea of a charioteer. Likewise the Creator is known by his works and the order of his providence.</p>
            <div class="tl-cite">Commentary on Ecclesiastes</div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">If the Lord does not grant success to the effort, both the effort and those who strive for it will be without success. It is up to us to start, but it is up to God to grant success. We start to build the house; God helps and perfects the construction. We guard our own city and are watchful of that decision to guard it, but God preserves it, undestroyed and undefeated by the aggressors.</p>
            <div class="tl-cite">Commentary on Ecclesiastes</div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">There is only one single human form that makes a person like God, but there are many into which he can transform himself. If he is cunning, he has the face of a fox; if he shows a poisonous, dangerous face, he has the face of a snake; if he looks wild, he has the face of a lion; if his face is ungovernable, flattering and desiring pleasures, he has the face of a dog... Thus it is the goal to get rid of all forms... in order to show that he has the face that God created.</p>
            <div class="tl-cite">Commentary on Ecclesiastes</div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">Whoever does not focus attention on perishable goods and does not think highly of them but knows that "it is better to be with Christ after death" thinks that the day of death is better than the day of birth. The latter is the beginning of many evils; the former, however, the end and termination of evil.</p>
            <div class="tl-cite">Commentary on Ecclesiastes</div>
          </div>
        </div>
      </div>

      <div class="tl-row" data-portrait="/portraits/epiphanius-of-salamis.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">403</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/Epiphanius_of_Salamis" target="_blank" rel="noopener">Epiphanius of Salamis</a></h2>
          <div class="tl-quote">
            <p class="tl-text">The Word tasted death once on our behalf, the death of the cross. He went to his death so that by death he might put death to death. The Word, becoming human flesh, did not suffer in his divinity but suffered with humanity.</p>
            <div class="tl-cite"><a href="/philippians/2/8">Ancoratus 92</a></div>
          </div>
        </div>
      </div>

      <div class="tl-row" data-portrait="/portraits/john-chrysostom.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">407</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/John_Chrysostom" target="_blank" rel="noopener">John Chrysostom</a></h2>
          <div class="tl-quote">
            <p class="tl-text">He is not rich who is surrounded by many possessions, but he who does not need many possessions; and he is not poor who possesses nothing, but he who requires many things... When, therefore, you see any one longing for many things, esteem him of all men the poorest, even though he possess all manner of wealth; again, when you see one who does not wish for many things, judge him to be of all men most affluent, even if he possess nothing.</p>
            <div class="tl-cite"><a href="/by_father.php?file=John%2520Chrysostom%2FFour%2520Discourses%252C%2520chiefly%2520on%2520the%2520parable%2520of%2520the%2520rich%2520man%2520and%2520Lazarus%2FDiscourse%25202.html#He+is+not+rich+who+is+surrounded+by+many+possessions%2C+but+he+who+does+not+need+many+possessions%3B+and+he+is+not+poor+who+possesses+nothing%2C+but+he+who+requires+many+things...+When%2C+therefore%2C+you+see+any+one+longing+for+many+things%2C+esteem+him+of+all+men+the+poorest%2C+even+though+he+possess+all+manner+of+wealth%3B+again%2C+when+you+see+one+who+does+not+wish+for+many+things%2C+judge+him+to+be+of+all+men+most+affluent%2C+even+if+he+possess+nothing.">Discourses on the Rich Man and Lazarus 2</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">Slave and free are simply names. What is a slave? It is a mere name. How many masters lie drunken upon their beds, while slaves stand by sober? Whom shall I call a slave? The one who is sober, or the one who is drunk? The one who is the slave of a man, or the one who is the captive of passion? The former has his slavery on the outside; the latter wears his captivity on the inside.</p>
            <div class="tl-cite">On Lazarus and the Rich Man 6</div>
          </div>
        </div>
      </div>

      <div class="tl-row" data-portrait="/portraits/jerome.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">420</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/Jerome" target="_blank" rel="noopener">Jerome</a></h2>
          <div class="tl-quote">
            <p class="tl-text">Some women, it is true, disfigure their faces so that they may appear to other people to fast. As soon as they catch sight of any one, they groan, they look down; they cover up their faces, except for one eye, which they keep free to see with. Their dress is somber, their girdles are of sackcloth, their hands and feet are dirty; only their stomachs—which cannot be seen—are hot with food.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Jerome%2FLetters%2Fletter_022.html#Some+women%2C+it+is+true%2C+disfigure+their+faces+so+that+they+may+appear+to+other+people+to+fast.+As+soon+as+they+catch+sight+of+any+one%2C+they+groan%2C+they+look+down%3B+they+cover+up+their+faces%2C+except+for+one+eye%2C+which+they+keep+free+to+see+with.+Their+dress+is+somber%2C+their+girdles+are+of+sackcloth%2C+their+hands+and+feet+are+dirty%3B+only+their+stomachs%E2%80%94which+cannot+be+seen%E2%80%94are+hot+with+food.">Letter 22.27</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">We have long felt that God is angry, yet we do not try to appease him. It is our sins that make the barbarians strong. It is our vices that vanquish Rome's soldiers... Therefore, if we wish to be lifted up, we must first prostrate ourselves.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Jerome%2FLetters%2Fletter_060.html#We+have+long+felt+that+God+is+angry%2C+yet+we+do+not+try+to+appease+him.+It+is+our+sins+that+make+the+barbarians+strong.+It+is+our+vices+that+vanquish+Rome%27s+soldiers...+Therefore%2C+if+we+wish+to+be+lifted+up%2C+we+must+first+prostrate+ourselves.">Letter 60.17</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">This very thing that we are speaking is part of death, and we do not understand. Our years will be pondered like a spider... Just as a spider casts out its threads, as it were, and runs this way and that, and weaves all day long, and the labor indeed is great, but the result is nothing: so too the life of men runs this way and that. We seek possessions, we prepare riches, we beget sons, we labor... and we do not understand that we are weaving a spider's web.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Jerome%2FHomilies%2520on%2520Psalms%2Fhomily_019.html#This+very+thing+that+we+are+speaking+is+part+of+death%2C+and+we+do+not+understand.+Our+years+will+be+pondered+like+a+spider...+Just+as+a+spider+casts+out+its+threads%2C+as+it+were%2C+and+runs+this+way+and+that%2C+and+weaves+all+day+long%2C+and+the+labor+indeed+is+great%2C+but+the+result+is+nothing%3A+so+too+the+life+of+men+runs+this+way+and+that.+We+seek+possessions%2C+we+prepare+riches%2C+we+beget+sons%2C+we+labor...+and+we+do+not+understand+that+we+are+weaving+a+spider%27s+web.">Homily 19, on Psalm 89</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">If you have fasted two or three days, do not think yourself better than others who do not fast. You fast and are angry; the other eats and wears a smiling face. You work off your irritation and hunger in quarrels. He uses food in moderation and gives God thanks.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Jerome%2FLetters%2Fletter_022.html#If+you+have+fasted+two+or+three+days%2C+do+not+think+yourself+better+than+others+who+do+not+fast.+You+fast+and+are+angry%3B+the+other+eats+and+wears+a+smiling+face.+You+work+off+your+irritation+and+hunger+in+quarrels.+He+uses+food+in+moderation+and+gives+God+thanks.">Letter 22.37</a></div>
          </div>
        </div>
      </div>

      <div class="tl-row" data-portrait="/portraits/theodore-of-mopsuestia.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">428</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/Theodore_of_Mopsuestia" target="_blank" rel="noopener">Theodore of Mopsuestia</a></h2>
          <div class="tl-quote">
            <p class="tl-text">Humility is the principle of all virtues: it removes any contrast, division or dissension from human beings and plants into them peace and charity. And through charity it grows and increases.</p>
            <div class="tl-cite">Commentary on John</div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">The Lord taught the disciples that there are many reasons for all these events and that they are certainly secret and unexplainable. And so, we always complain about events whose causes we ignore, but then we also learn that nothing happens in vain. This knowledge will be given to us in the future world, because what is hidden now will be revealed to us.</p>
            <div class="tl-cite">Commentary on John</div>
          </div>
        </div>
      </div>

      <div class="tl-row" data-portrait="/portraits/augustine-of-hippo.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">430</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/Augustine_of_Hippo" target="_blank" rel="noopener">Augustine of Hippo</a></h2>
          <div class="tl-quote">
            <p class="tl-text">Our heart is restless until it rests in you.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Augustine%2520of%2520Hippo%2FConfessions%2FBook%25201.html#Thou+movest+us+to+delight+in+praising+Thee%3B+for+Thou+hast+formed+us+for+Thyself%2C+and+our+hearts+are+restless+till+they+find+rest+in+Thee">Confessions 1.1</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">Once for all, then, a short precept is given thee: Love, and do what thou wilt: whether thou hold thy peace, through love hold thy peace; whether thou cry out, through love cry out; whether thou correct, through love correct; whether thou spare, through love do thou spare: let the root of love be within, of this root can nothing spring but what is good.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Augustine%2520of%2520Hippo%2FTen%2520Homilies%2520on%2520the%2520First%2520Epistle%2520of%2520John%2FHomily%25207.html#Once+for+all%2C+then%2C+a+short+precept+is+given+thee%3A+Love%2C+and+do+what+thou+wilt%3A+whether+thou+hold+thy+peace%2C+through+love+hold+thy+peace%3B+whether+thou+cry+out%2C+through+love+cry+out%3B+whether+thou+correct%2C+through+love+correct%3B+whether+thou+spare%2C+through+love+do+thou+spare%3A+let+the+root+of+love+be+within%2C+of+this+root+can+nothing+spring+but+what+is+good.">Ten Homilies on 1 John 7</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">People love truth when it shines on them and hate it when it rebukes them. For, because they are not willing to be deceived but definitely want to practice the art of deception, they love truth when it reveals itself and hate it when it reveals them.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Augustine%2520of%2520Hippo%2FConfessions%2FBook%252010.html#They+love+truth+when+she+shines+on+them%2C+and+hate+her+when+she+rebukes+them.+For%2C+because+they+are+not+willing+to+be+deceived%2C+and+wish+to+deceive%2C+they+love+her+when+she+reveals+herself%2C+and+hate+her+when+she+reveals+them">Confessions 10.23.34</a></div>
          </div>
        </div>
      </div>

      <div class="tl-row" data-portrait="/portraits/john-cassian.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">435</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/John_Cassian" target="_blank" rel="noopener">John Cassian</a></h2>
          <div class="tl-quote">
            <p class="tl-text">Whatever the mind has been thinking about before it prays will certainly come to it while it is praying. Therefore, before we begin to pray, we ought to be trying to be the kind of people whom we wish God to find when we pray. The mind is conditioned by its recent state. In prayer, the mind remembers recent acts or thoughts and experiences, sees them dancing before it like ghosts.</p>
            <div class="tl-cite"><a href="/by_father.php?file=John%2520Cassian%2FConferences%2520of%2520John%2520Cassian%2520-%2520Part%2520I%2F9.%2520The%2520First%2520Conference%2520of%2520Abbot%2520Isaac.html#For+whatever+our+mind+has+been+thinking+of+before+the+hour+of+prayer%2C+is+sure+to+occur+to+us+while+we+are+praying+through+the+activity+of+the+memory.+Wherefore+what+we+want+to+find+ourselves+like+while+we+are+praying%2C+that+we+ought+to+prepare+ourselves+to+be+before+the+time+for+prayer.+For+the+mind+in+prayer+is+formed+by+its+previous+condition%2C+and+when+we+are+applying+ourselves+to+prayer+the+images+of+the+same+actions+and+words+and+thoughts+will+dance+before+our+eyes">Conferences 9.3.3</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">A saint is different from a sinner, not because he or she is not tempted in the same way but because he or she is not defeated even by a great assault, while the other is overcome even by a slight temptation... Most certainly there is no room for victory where there is no struggle and conflict.</p>
            <div class="tl-cite"><a href="/by_father.php?file=John%2520Cassian%2FConferences%2520of%2520John%2520Cassian%2520-%2520Part%2520III%2F18.%2520Conference%2520of%2520Abbot%2520Piamun.html#For+a+saint+does+not+differ+from+a+sinner+in+this%2C+that+he+is+not+himself+tempted+in+the+same+way%2C+but+because+he+is+not+worsted+even+by+a+great+assault%2C+while+the+other+is+overcome+even+by+a+slight+temptation.+For+the+fortitude+of+any+good+man+would+not%2C+as+we+said%2C+be+worthy+of+praise%2C+if+his+victory+was+gained+without+his+being+tempted%2C+as+most+certainly+there+is+no+room+for+victory+where+there+is+no+struggle+and+conflict">Conference 3.18.13</a></div>
          </div>
        </div>
      </div>

      <div class="tl-row" data-portrait="/portraits/cyril-of-alexandria.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">444</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/Cyril_of_Alexandria" target="_blank" rel="noopener">Cyril of Alexandria</a></h2>
          <div class="tl-quote">
            <p class="tl-text">"Do not seek," he says, "the one who" always "lives," who in his own nature is life, "among the dead. He is not here," that is, dead and in the tomb, "but he has been raised." He has become a way of ascent to immortality not only for himself but also for us... And so he has become the death of death.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Cyril%2520of%2520Alexandria%2FCommentary%2520on%2520the%2520Gospel%2520of%2520Luke%2FSermons%2520146-156%2520%2528Luke%252022-39-24-45%2529.html#Why+seek+you+the+living+among+the+dead%3F+He+is+not+here%2C+but+is+risen.+For+the+Word+of+God+ever+lives%2C+and+is+by+His+own+nature+Life%3A+but+when+He+humbled+Himself+to+emptying%2C+and+submitted+to+be+made+like+to+us%2C+He+tasted+death.+But+this+proved+to+be+the+death+of+death%3A+for+He+arose+from+the+dead%2C+to+be+the+way+whereby+not+Himself+so+much+but+we+rather+return+to+incorruption.">Fragment 317.24</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">For that He utterly abolished death, and effaced destruction, and spoiled hell, and overthrew the tyranny of the enemy, and took away the sin of the world, and opened the gates above to the dwellers upon earth, and united earth to heaven: these things proved Him to be, as I said, in truth God.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Cyril%2520of%2520Alexandria%2FCommentary%2520on%2520the%2520Gospel%2520of%2520Luke%2FSermons%252047-56%2520%2528Luke%25209-1-56%2529.html#For+that+He+utterly+abolished+death%2C+and+effaced+destruction%2C+and+spoiled+hell%2C+and+overthrew+the+tyranny+of+the+enemy%2C+and+took+away+the+sin+of+the+world%2C+and+opened+the+gates+above+to+the+dwellers+upon+earth%2C+and+united+earth+to+heaven%3A+these+things+proved+Him+to+be%2C+as+I+said%2C+in+truth+God.">Commentary on Luke, Sermon 49</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">Death overcame our forefather Adam on account of his transgression and like a fierce wild animal it pounced on him and carried him off amid lamentation... But all this came to an end with Christ. Striking down death, he rose up on the third day... Therefore every tear is taken away. For believing that Christ will surely raise the dead, we do not weep over them, nor are we overwhelmed by inconsolable grief like those who have no hope.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Cyril%2520of%2520Alexandria%2FCommentary%2520on%2520Isaiah.html#Therefore+death+indeed+prevailed+over+our+forefather+Adam+because+of+the+transgression%2C+and+like+some+savage+and+cruel+beast%2C+it+leaped+upon+and+seized+him.+Henceforth%2C+then%2C+laments+and+wailings+and+tears+and+mourning+for+the+dead+have+prevailed+over+those+on+the+earth.+But+it+has+ceased+in+Christ.+For+on+the+third+day+he+came+back+to+life%2C+having+trampled+on+death%2C+and+having+become+a+way+for+human+nature+to+escape+corruption.+For+he+has+become+the+firstborn+from+the+dead%2C+and+the+firstfruits+of+those+who+have+fallen+asleep.+And+what+is+after+the+firstfruits+and+the+first+will+certainly+follow%2C+that+is%2C+we+ourselves.+Therefore+the+suffering+was+turned+into+joy%2C+and+the+sackcloth+was+torn%3B+and+we+have+girded+ourselves+with+God-given+gladness%2C+so+that+we+say+rejoicing%3A+%22Where%2C+O+death%2C+is+your+victory%3F+Where%2C+O+Hades%2C+is+your+sting%3F+And+the+sting+of+death+is+sin%2C+it+says.%22+Therefore+every+tear+has+been+taken+away%3B+for+believing+that+Christ+will+in+every+way+and+certainly+raise+the+dead%2C+we+do+not+shed+tears+over+them%2C+nor+do+we+descend+into+excessive+sorrows%2C+like+the+rest+who+have+no+hope">Commentary on Isaiah 3.1</a></div>
          </div>
        </div>
      </div>

      <div class="tl-row" data-portrait="/portraits/peter-chrysologus.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">450</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/Peter_Chrysologus" target="_blank" rel="noopener">Peter Chrysologus</a></h2>
          <div class="tl-quote">
            <p class="tl-text">The women were first to honor the risen Christ, the apostles first to suffer for him. The women were ready with spices; the apostles prepared for scourges. The women entered the tomb; the apostles would soon enter the dungeon. The women hastened to express their eulogy; the apostles embraced chains for his sake. The women poured oils; the apostles poured out their blood.</p>
            <div class="tl-cite">Sermon 79</div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">Paul asks—rather, God himself is asking through Paul—for God has greater desire to be loved than feared. God is asking because he wants to be not so much a Lord as a Father.</p>
            <div class="tl-cite">Sermon 108</div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">Her bosom was disturbed, her mind recoiled, and her whole state became one of trembling when God, whom the whole of creation does not contain, placed his whole Self inside her bosom and made himself a man.</p>
            <div class="tl-cite">Sermon 140</div>
          </div>
        </div>
      </div>

      <div class="tl-row" data-portrait="/portraits/theodoret-of-cyrus.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">458</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/Theodoret" target="_blank" rel="noopener">Theodoret of Cyrus</a></h2>
          <div class="tl-quote">
            <p class="tl-text">Christ was called what we are in order to call us to be what he is.</p>
            <div class="tl-cite">Commentary on 2 Corinthians</div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">Let us then bravely bear the ills that befall us. It is in war that heroes are discerned; in conflicts that athletes are crowned; in the surge of the sea that the art of the helmsman is shown; in the fire that the gold is tried... Let us, then, stretch out our hands to them that lie low, let us tend their wounds and set them at their post to fight the devil.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Theodoret%2520of%2520Cyrus%2FLetters%2520of%2520the%2520Blessed%2520Theodoret.html#Let+us+then+bravely+bear+the+ills+that+befall+us.+It+is+in+war+that+heroes+are+discerned%3B+in+conflicts+that+athletes+are+crowned%3B+in+the+surge+of+the+sea+that+the+art+of+the+helmsman+is+shown%3B+in+the+fire+that+the+gold+is+tried...+Let+us%2C+then%2C+stretch+out+our+hands+to+them+that+lie+low%2C+let+us+tend+their+wounds+and+set+them+at+their+post+to+fight+the+devil.">Letter 78</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">If you observe a most mighty and magnificent building, you admire the builder; and if you see a skillfully and beautifully designed ship, you think of the shipwright; and at the sight of a painting the painter comes to mind. Much more, to be sure, does the sight of creation lead the viewers to the Creator.</p>
            <div class="tl-cite">Commentary on Psalms 19</div>
          </div>
        </div>
      </div>

      <div class="tl-row" data-portrait="/portraits/leo-the-great.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">461</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/Pope_Leo_I" target="_blank" rel="noopener">Leo the Great</a></h2>
          <div class="tl-quote">
            <p class="tl-text">Christian, acknowledge thy dignity, and becoming a partner in the Divine nature, refuse to return to the old baseness by degenerate conduct. Remember the Head and the Body of which thou art a member... By the mystery of Baptism thou wast made the temple of the Holy Ghost... because thy purchase money is the blood of Christ, because He shall judge thee in truth Who ransomed thee in mercy.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Leo%2520the%2520Great%2FSermons%2FSermon%252021%2520On%2520the%2520Feast%2520of%2520the%2520Nativity%252C%25201.html#Christian%2C+acknowledge+thy+dignity%2C+and+becoming+a+partner+in+the+Divine+nature%2C+refuse+to+return+to+the+old+baseness+by+degenerate+conduct.+Remember+the+Head+and+the+Body+of+which+thou+art+a+member...+By+the+mystery+of+Baptism+thou+wast+made+the+temple+of+the+Holy+Ghost...+because+thy+purchase+money+is+the+blood+of+Christ%2C+because+He+shall+judge+thee+in+truth+Who+ransomed+thee+in+mercy.">Nativity Sermon</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">He himself says, "Be holy, for I am holy," that is to say, choose me and keep away from what displeases me. Do what I love; love what I do. If what I order seems difficult, come back to me who ordered it, so that from where the command was given help might be offered... Let me be your food and drink. None desire in vain what is mine, for those who stretch out toward me seek me because I first sought them.</p>
            <div class="tl-cite">Sermon</div>
          </div>
        </div>
      </div>

      <div class="tl-era">
        <div class="tl-era-line"></div>
        <div class="tl-era-label">The long transmission</div>
        <div class="tl-era-line"></div>
      </div>

      <div class="tl-row" data-portrait="/portraits/desert-fathers.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">500</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/Desert_Fathers" target="_blank" rel="noopener">Desert Fathers</a></h2>
          <div class="tl-quote">
            <p class="tl-text">Two hermits lived together for many years without a quarrel. One said to the other, "Let's have a quarrel with each other, as other men do." The other answered, "I don't know how a quarrel happens." The first said, "Look here, I put a brick between us, and I say, 'That's mine.' Then you say, 'No, it's mine.' That is how you begin a quarrel." So they put a brick between them, and one of them said, "That's mine." The other said, "No; it's mine." He answered, "Yes, it's yours. Take it away." They were unable to argue with each other.</p>
            <div class="tl-cite">Sayings of the Desert Fathers</div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">A brother who was hurt by another brother went to the Theban Sisois and said, "I want to get back at a brother who has hurt me." The hermit begged him, "Don't do that, my son, leave vengeance in the hands of God." But he said, "I can't rest till I get my own back." The hermit said, "My brother, let us pray." He stood and said, "O God, we have no further need of you, for we can take vengeance by ourselves." The brother heard it and fell at the hermit's feet, saying, "I won't quarrel with my brother any longer; I beg you to forgive me."</p>
            <div class="tl-cite">Sayings of the Desert Fathers</div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">Arsenius always used to say this, "Why, words, did I let you get out? I have often been sorry that I have spoken, never that I have been silent."</p>
            <div class="tl-cite">Sayings of the Desert Fathers</div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">A brother asked a hermit, "Suppose there are two monks: one stays quietly in his cell, fasting for six days at a time, laying many hardships on himself: and the other ministers to the sick. Which of them is more pleasing to God?" He replied, "Even if the brother who fasts six days hung himself up by his nose, he wouldn't be the equal of him who ministers to the sick."</p>
            <div class="tl-cite">Sayings of the Desert Fathers</div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">Once some brothers came to visit Antony, and Joseph was with them. Antony, wanting to test them, began to speak about holy Scripture. He asked the younger monks first the meaning of text after text, and each of them answered as well as he could. To each he said, "You have not yet found the right answer." Then he said to Joseph, "What do you think is the meaning of this word?" He replied, "I don't know." Antony said, "Indeed Joseph alone has found the true way, for he said he did not know."</p>
            <div class="tl-cite">Sayings of the Desert Fathers</div>
          </div>
        </div>
      </div>

      <div class="tl-row" data-portrait="/portraits/philoxenus-of-mabbug.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">523</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/Philoxenus_of_Mabbug" target="_blank" rel="noopener">Philoxenus of Mabbug</a></h2>
          <div class="tl-quote">
            <p class="tl-text">The care for human riches is a path which hath no ending in this life, for however far a man may travel along it, it lengtheneth out before his footsteps, and there is nothing which breaketh it except death... "But your weariness begetteth weariness, and your labour bringeth forth labour, and your riches gather together poverty, and your rest is tribulation, and your enjoyment is affliction... for the path of the desire of riches which ye have trodden of your own freewill hath no end; but if ye will come to Me by My road it will come to an end."</p>
            <div class="tl-cite"><a href="/by_father.php?file=Philoxenus%2520of%2520Mabbug%2F13%2520Ascetic%2520Discourses%2FDiscourse%25209%2520--%2520Second%2520Discourse%2520on%2520Poverty.html#The+care+for+human+riches+is+a+path+which+hath+no+ending+in+this+life%2C+for+however+far+a+man+may+travel+along+it%2C+it+lengtheneth+out+before+his+footsteps%2C+and+there+is+nothing+which+breaketh+it+except+death...+%22But+your+weariness+begetteth+weariness%2C+and+your+labour+bringeth+forth+labour%2C+and+your+riches+gather+together+poverty%2C+and+your+rest+is+tribulation%2C+and+your+enjoyment+is+affliction...+for+the+path+of+the+desire+of+riches+which+ye+have+trodden+of+your+own+freewill+hath+no+end%3B+but+if+ye+will+come+to+Me+by+My+road+it+will+come+to+an+end.%22">13 Ascetic Discourses, Discourse 9</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">One man feareth lest he be struck, and this is the fear of slaves; another man feareth lest he suffer loss, and this is the fear of hirelings; another man feareth lest he cause distress, and this is the fear of friends; and another man feareth lest his name be not handed down to posterity, and this is the fear of children... the Prophets, like friends, feared to cause distress to God, Whom they loved, but the Jews, like slaves, were afraid of the rod of His chastisement.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Philoxenus%2520of%2520Mabbug%2F13%2520Ascetic%2520Discourses%2FDiscourse%25207%2520--%2520Second%2520Discourse%2520on%2520the%2520Fear%2520of%2520God.html#One+man+feareth+lest+he+be+struck%2C+and+this+is+the+fear+of+slaves%3B+another+man+feareth+lest+he+suffer+loss%2C+and+this+is+the+fear+of+hirelings%3B+another+man+feareth+lest+he+cause+distress%2C+and+this+is+the+fear+of+friends%3B+and+another+man+feareth+lest+his+name+be+not+handed+down+to+posterity%2C+and+this+is+the+fear+of+children...+the+Prophets%2C+like+friends%2C+feared+to+cause+distress+to+God%2C+Whom+they+loved%2C+but+the+Jews%2C+like+slaves%2C+were+afraid+of+the+rod+of+His+chastisement.">13 Ascetic Discourses, Discourse 7</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">For as God is over created things so art thou god over thy lusts, and as by the will of the Creator created things exist, and if He willeth not they exist not, so also according to thy will are thy lusts, and at thy will they become nothing... If thou wishest, they are thy passions; and if thou wishest, they do not exist. From thee springeth up the cause of thy lust, and from thee is born the destruction thereof.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Philoxenus%2520of%2520Mabbug%2F13%2520Ascetic%2520Discourses%2FDiscourse%252011%2520--%2520On%2520Abstinence.html#is+over+created+things+so+art+thou+god+over+thy+lusts%2C+and+as+by+the+will+of+the+Creator+created+things+exist%2C+and+if+He+willeth+not+they+exist+not%2C+so+also+according+to+thy+will+are+thy+lusts%2C+and+at+thy+will+they+become+nothing.+If+thou+wishest%2C+they+are+thy+passions%3B+and+if+thou+wishest%2C+they+do+not+exist.+From+thee+springeth+up+the+cause+of+thy+lust%2C+and+from+thee+is+born+the+destruction+thereof">13 Ascetic Discourses, Discourse 11</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">Remember thou then at all times that God looketh at thee, and do thou thyself also look at Him inwardly, even as He seeth thee inwardly, and sin shall not abide in thy thoughts. For as in the place whereupon the sun looketh darkness abideth not, even so in the soul upon which God looketh, and which itself also feeleth that He is regarding it, the darkness of wickedness remaineth not.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Philoxenus%2520of%2520Mabbug%2F13%2520Ascetic%2520Discourses%2FDiscourse%25206%2520--%2520First%2520Discourse%2520on%2520the%2520Fear%2520of%2520God.html#Remember+thou+then+at+all+times+that+God+looketh+at+thee%2C+and+do+thou+thyself+also+look+at+Him+inwardly%2C+even+as+He+seeth+thee+inwardly%2C+and+sin+shall+not+abide+in+thy+thoughts.+For+as+in+the+place+whereupon+the+sun+looketh+darkness+abideth+not%2C+even+so+in+the+soul+upon+which+God+looketh%2C+and+which+itself+also+feeleth+that+He+is+regarding+it%2C+the+darkness+of+wickedness+remaineth+not.">13 Ascetic Discourses, Discourse 6</a></div>
          </div>
        </div>
      </div>

      <div class="tl-row" data-portrait="/portraits/caesarius-of-arles.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">542</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/Caesarius_of_Arles" target="_blank" rel="noopener">Caesarius of Arles</a></h2>
          <div class="tl-quote">
            <p class="tl-text">One who claims to abide in Christ ought to walk as he walked... Two feet are needed to run along this highway; they are humility and charity. Everyone wants to get to the top—well, the first step to take is humility. Why take strides that are too big for you—do you want to fall instead of going up? Begin with the first step, humility, and you will already be climbing.</p>
            <div class="tl-cite">Sermon 159</div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">God does not want to kill the sinner, but his sin. Like a good doctor he wants to strike the disease, not the person who is ill. But, what is worse, we often despise the doctor and love our sickness: we love our sin and despise God... God wants to kill sin, not to strike the sinner.</p>
            <div class="tl-cite">Sermon 17.4</div>
          </div>
        </div>
      </div>

      <div class="tl-row" data-portrait="/portraits/oecumenius.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">550</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/Oecumenius" target="_blank" rel="noopener">Oecumenius</a></h2>
          <div class="tl-quote">
            <p class="tl-text">For he, to whom all things common belong, has given abundantly and freely: heaven, earth, air, life, food. But greed, having taken tyranny as a partner, has seized many of the common things and made them private.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Oecumenius%2FCommentary%2520on%2520the%2520Pastoral%2520Epistles%2FTranslation-Litteral.html#For+he%2C+to+whom+all+things+common+belong%2C+has+given+abundantly+and+freely%3A+heaven%2C+earth%2C+air%2C+life%2C+food.+But+greed%2C+having+taken+tyranny+as+a+partner%2C+has+seized+many+of+the+common+things+and+made+them+private.">Commentary on 1 Timothy</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">For such is every sin. It is sweet in its operation, but bitter in its outcome.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Oecumenius%2FCommentary%2520on%2520Revelation%2FTranslation-Litteral.html#For+such+is+every+sin.+It+is+sweet+in+its+operation%2C+but+bitter+in+its+outcome.">Commentary on Revelation</a></div>
          </div>
        </div>
      </div>

      <div class="tl-row" data-portrait="/portraits/cosmas-indicopleustes.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">550</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/Cosmas_Indicopleustes" target="_blank" rel="noopener">Cosmas Indicopleustes</a></h2>
          <div class="tl-quote">
            <p class="tl-text">So they stood as spectators merely, beholding the things made along with them, and after them; for they beheld the heaven made of nothing, and were struck with astonishment; they beheld the sea parted off, and were lost in wonder; they saw the earth in her beautiful apparel, and were thrilled with delight.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Cosmas%2520Indicopleustes%2FThe%2520Christian%2520Topography%2FBook%252010.html#So+they+stood+as+spectators+merely%2C+beholding+the+things+made+along+with+them%2C+and+after+them%3B+for+they+beheld+the+heaven+made+of+nothing%2C+and+were+struck+with+astonishment%3B+they+beheld+the+sea+parted+off%2C+and+were+lost+in+wonder%3B+they+saw+the+earth+in+her+beautiful+apparel%2C+and+were+thrilled+with+delight.">Christian Topography, Book 10</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">Consider, O men, that the bare grain when sown in the earth, in the first place undergoes dissolution, for if this first dies, it then grows up by the power and providence of God, and reappears richly endowed, artfully contrived and exceeding beautiful; instead of one grain, a great number, instead of being bare, enfolded in a sheath... This very body then which has been corrupted and changed into earth, and again sprouts up from the earth multiplied and of an admirable beauty, is a work full of wisdom and art.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Cosmas%2520Indicopleustes%2FThe%2520Christian%2520Topography%2FBook%25207.html#Consider%2C+O+men%2C+%5B281%5D+that+the+bare+grain+when+sown+in+the+earth%2C+in+the+first+place+undergoes+dissolution%2C+for+if+this%2C+he+says%2C+first+dies%2C+it+then+grows+up+by+the+power+and+providence+of+God%2C+and+reappears+richly+endowed%2C+artfully+contrived+and+exceeding+beautiful%3B+instead+of+one+grain%2C+a+great+number%2C+instead+of+being+bare%2C+enfolded+in+a+sheath%2C+instead+of+being+easily+plucked+up+and+trodden+underfoot%2C+firmly+rooted+and+aided+by+having+ears+to+keep+it+safe+from+all+that+could+do+it+harm.+This+very+body+then+which+has+been+corrupted+and+changed+into+earth%2C+and+again+sprouts+up+from+the+earth+multiplied+and+of+an+admirable+beauty%2C+is+a+work+full+of+wisdom+and+art">Christian Topography, Book 7</a></div>
          </div>
        </div>
      </div>

      <div class="tl-row" data-portrait="/portraits/cassiodorus.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">585</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/Cassiodorus" target="_blank" rel="noopener">Cassiodorus</a></h2>
          <div class="tl-quote">
            <p class="tl-text">Paul begged that the flesh's thorn be removed from him, but he was not heard by the Lord. The devil prayed that he might strike Job with the harshest of disasters, and we know that this was subsequently granted him. But Paul was denied the fulfillment of his prayer for his glory, whereas the devil was granted his for the devil's pain. Thus it is often an advantage not to be heard even though postponement of our desires depresses us.</p>
            <div class="tl-cite">Explanation of the Psalms 21.3</div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">We thank you, Lord, for this arrangement. What would the devil do if free, when he afflicts the world when bound?</p>
            <div class="tl-cite">Exposition of the Psalms 36:35</div>
          </div>
        </div>
      </div>

      <div class="tl-row" data-portrait="/portraits/gregory-the-dialogist.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">604</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/Pope_Gregory_I" target="_blank" rel="noopener">Gregory the Dialogist</a></h2>
          <div class="tl-quote">
            <p class="tl-text">For behold, the voice of all proclaims Christ, but the life of all does not proclaim Him. Most follow God with their voices, but flee from Him by their conduct.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Gregory%2520the%2520Dialogist%2F40%2520Homilies%2520on%2520the%2520Gospels.html#For+behold%2C+the+voice+of+all+proclaims+Christ%2C+but+the+life+of+all+does+not+proclaim+Him.+Most+follow+God+with+their+voices%2C+but+flee+from+Him+by+their+conduct.">Forty Gospel Homilies, Homily 19</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">Wrongs which we suffer from strangers, pain us less than those we suffer from men on whose affections we had counted; for besides the bodily affliction, there is then the pain of lost affection.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Gregory%2520the%2520Dialogist%2F40%2520Homilies%2520on%2520the%2520Gospels.html#Evils+inflicted+by+strangers+cause+lesser+pain.+But+those+torments+rage+more+fiercely+within+us+which+we+suffer+from+those+in+whose+minds+we+had+confidence%2C+because+along+with+bodily+harm%2C+the+evils+of+lost+love+torment+us.">Catena Aurea (Hom. in Ev. xxxv. 3)</a></div>
          </div>
        </div>
      </div>

      <div class="tl-row" data-portrait="/portraits/andreas-of-caesarea.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">614</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/Andreas_of_Caesarea" target="_blank" rel="noopener">Andreas of Caesarea</a></h2>
          <div class="tl-quote">
            <p class="tl-text">...from this converse that profits the soul we should understand that the angel, sad and from afar, follows a person who has been darkened by many sins and enters into the church. But if this person has been made contrite and confesses from the heart to him who delights in mercy that he has rejected his former life and has converted to a better one, when he departs [from the church], the angel leads the way cheerfully and joyfully, while the wicked demon, having been shamed, follows behind at a distance.</p>
            <div class="tl-cite">Commentary on the Apocalypse 16:7</div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">How deep is the divine goodness! For he brings a healing that is commensurate with the wound.</p>
            <div class="tl-cite">Commentary on the Apocalypse 11:5-6</div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">For just as when Adam was sleeping, the woman was formed through the taking of the rib, so also the church, formed through the shedding of blood from the side of Christ as he was sleeping voluntarily on the cross through death, was united with him who suffered for us.</p>
            <div class="tl-cite">Commentary on the Apocalypse 21:9</div>
          </div>
        </div>
      </div>

      <div class="tl-row" data-portrait="/portraits/maximus-the-confessor.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">662</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/Maximus_the_Confessor" target="_blank" rel="noopener">Maximus the Confessor</a></h2>
          <div class="tl-quote">
            <p class="tl-text">The outcome of every affliction endured for the sake of virtue is joy, the outcome of every labor is rest, and the outcome of every shameful treatment is glory. In short, the outcome of all sufferings for the sake of virtue is to be with God, to remain with him forever and to enjoy eternal rest.</p>
            <div class="tl-cite">Various Texts on Theology, First Century 41-44</div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">The one who has come to understand the weakness of human nature has had experience of the divine power. And such a person who because of divine power has succeeded in some things and is eager to succeed in others never looks down on anyone. For he knows that in the same way that God has helped him and freed him from many passions and hardships, so can he help everyone when he wishes, especially those who are striving for his sake.</p>
            <div class="tl-cite">The Four Hundred Chapters on Love 2.38-39</div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">Then the prayer is not just a matter of words, blurted out meaninglessly with the empty echo of the tongue, but powerful and living and inspired with the spirit of the commandments. For the true basis of prayer and supplication is the fulfillment of the commandments by virtue.</p>
            <div class="tl-cite">Catena (on James 5:16)</div>
          </div>
        </div>
      </div>

      <div class="tl-row" data-portrait="/portraits/bede.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">735</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/Bede" target="_blank" rel="noopener">Bede</a></h2>
          <div class="tl-quote">
            <p class="tl-text">Fear pierces, but do not be afraid: love enters, which heals what fear wounds.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Bede%2FCommentary%2520on%2520the%2520Catholic%2520Epistles.html#Fear+pierces%2C+but+do+not+be+afraid%3A+love+enters%2C+which+heals+what+fear+wounds.">Commentary on the Catholic Epistles (1 John 4:18)</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">he who attains the ornament of divine words by meditation but follows it with a bad life has a golden ring in his nose; but like a pig, he does not cease to turn the earth, because what he perceived by the scent of knowledge, he defiled by an impure action.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Bede%2FCommentary%2520on%2520Proverbs.html#he+who+attains+the+ornament+of+divine+words+by+meditation+but+follows+it+with+a+bad+life+has+a+golden+ring+in+his+nose%3B+but+like+a+pig%2C+he+does+not+cease+to+turn+the+earth%2C+because+what+he+perceived+by+the+scent+of+knowledge%2C+he+defiled+by+an+impure+action.">Commentary on Proverbs 11:22</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">It is a lesser victory to conquer cities, because they are conquered externally; but what is conquered by patience is greater, because the spirit conquers itself, and submits itself to itself, when patience brings it down in the humility of tolerance.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Bede%2FCommentary%2520on%2520Proverbs.html#It+is+a+lesser+victory+to+conquer+cities%2C+because+they+are+conquered+externally%3B+but+what+is+conquered+by+patience+is+greater%2C+because+the+spirit+conquers+itself%2C+and+submits+itself+to+itself%2C+when+patience+brings+it+down+in+the+humility+of+tolerance.">Commentary on Proverbs 16:32</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">The certainty of our hope is prefigured by the egg. No offspring is as yet discernible in the egg, but the birth of the bird to come is hoped for. The faithful do not yet look upon the glory of the fatherland on high in which they believe at the present time, but they await its coming in hope.</p>
            <div class="tl-cite">Homilies on the Gospels 11.14</div>
          </div>
        </div>
      </div>

      <div class="tl-row" data-portrait="/portraits/john-damascene.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">749</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/John_of_Damascus" target="_blank" rel="noopener">John Damascene</a></h2>
          <div class="tl-quote">
            <p class="tl-text">Behold, the glorification of matter, which you despise! What is more insignificant than colored goatskins? Are not blue and purple and scarlet merely colors? Behold the handiwork of men becoming the likeness of the cherubim! ... I do not worship matter. I worship the Creator of matter, who became matter for me, taking up his abode in matter and accomplishing my salvation through matter.</p>
            <div class="tl-cite">On Divine Images 14</div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">Shine, shine, O new Jerusalem, for the glory of the Lord has shone on you. Rejoice and be glad, O Zion! And you, O immaculate, O Mother of God, exult with Job in the resurrection of your Son. Christ is risen, and he has crushed death and raised the dead: rejoice, therefore, O nations of the earth! ... On this day, the whole creation rejoices and exults, for Christ is risen and hades despoiled.</p>
            <div class="tl-cite">The Canon of Pascha, Ninth Ode</div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">Those who trust in idols are foolish. Idols are their own creations, things they made with their own hands, but they turn around and say, "These idols are our creators." How can these people say that something they made is their creator? Moreover, they guard their idols, so that they will not be stolen by thieves. What foolishness! If idols cannot guard and protect themselves, how can they guard and save others?</p>
            <div class="tl-cite">Barlaam and Joseph 10</div>
          </div>
        </div>
      </div>

      <div class="tl-era">
        <div class="tl-era-line"></div>
        <div class="tl-era-label">The medieval inheritance</div>
        <div class="tl-era-line"></div>
      </div>

      <div class="tl-row" data-portrait="/portraits/alcuin-of-york.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">804</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/Alcuin" target="_blank" rel="noopener">Alcuin of York</a></h2>
          <div class="tl-quote">
            <p class="tl-text">for in a harp some strings are stretched more tightly and others more loosely, but, albeit stretched differently, they do not at all produce each a different song: it is the same with the different members in the body of Christ: some imitate his sufferings more fully and others less, but they resound with one praise in harmony.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Alcuin%2520of%2520York%2FCommentary%2520and%2520the%2520Questions%2520and%2520Answers%2520Manual%2FTranslation-Van%2520Der%2520Pas-Litteral.html#for+in+a+harp+some+strings+are+stretched+more+tightly+and+others+more+loosely%2C+but%2C+albeit+stretched+differently%2C+they+do+not+at+all+produce+each+a+different+song%3A+it+is+the+same+with+the+different+members+in+the+body+of+Christ%3A+some+imitate+his+sufferings+more+fully+and+others+less%2C+but+they+resound+with+one+praise+in+harmony.">Commentary on Revelation 5:8</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">the sacred Scripture is for us a food in its obscure passages, which cannot be swallowed down unless they are chewed by means of explanation, and a drink in its obvious passages, which we easily drink just as they are found, without explanation.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Alcuin%2520of%2520York%2FCommentary%2520and%2520the%2520Questions%2520and%2520Answers%2520Manual%2FTranslation-Van%2520Der%2520Pas-Litteral.html#the+sacred+Scripture+is+for+us+a+food+in+its+obscure+passages%2C+which+cannot+be+swallowed+down+unless+they+are+chewed+by+means+of+explanation%2C+and+a+drink+in+its+obvious+passages%2C+which+we+easily+drink+just+as+they+are+found%2C+without+explanation.">Commentary on Revelation 10:9</a></div>
          </div>
        </div>
      </div>

      <div class="tl-row" data-portrait="/portraits/rabanus-maurus.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">856</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/Rabanus_Maurus" target="_blank" rel="noopener">Rabanus Maurus</a></h2>
          <div class="tl-quote">
            <p class="tl-text">Three things cause a man to speak loud; when the person he speaks to is at a distance, or is deaf, or if the speaker be angry; and all these three were then found in the human race.</p>
            <div class="tl-cite">Catena Aurea (on Matthew 3:3)</div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">For when hypocrites fight against lust, or against gluttony, or against bodily sins through abstinence, and, taming the flesh by fasting or by vigils and the other works of abstinence, do not keep careful watch over themselves against spiritual wickedness, so that they do not beware of pride or envy or greed, they are sometimes conquered in conquering, because while they overcome in one way of life, in this victory, being careless, they are crushed by others.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Rabanus%2520Maurus%2FCommentary_on_1_Maccabees.html#For+when+hypocrites+fight+against+lust%2C+or+against+gluttony%2C+or+against+bodily+sins+through+abstinence%2C+and%2C+taming+the+flesh+by+fasting+or+by+vigils+and+the+other+works+of+abstinence%2C+do+not+keep+careful+watch+over+themselves+against+spiritual+wickedness%2C+so+that+they+do+not+beware+of+pride+or+envy+or+greed%2C+they+are+sometimes+conquered+in+conquering%2C+because+while+they+overcome+in+one+way+of+life%2C+in+this+victory%2C+being+careless%2C+they+are+crushed+by+others.">Commentary on 1 Maccabees 6:43</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">But whenever we are not heard when we pray, it is either because we ask something adverse to the means of our salvation; or because the perverseness of those for whom we ask hinders its being granted to them; or because the performance of our request is put off to a future time, that our desires may wax stronger, and so may have more perfect capacity for the joys they seek after.</p>
            <div class="tl-cite">Catena Aurea (on Matthew 21:22)</div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">For every temporary thing, when compared to what is everlasting, seems as if it no longer exists, since it is consumed by eternity.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Rabanus%2520Maurus%2FCommentary_on_the_Wisdom_Of_Solomon.html#For+every+temporary+thing%2C+when+compared+to+what+is+everlasting%2C+seems+as+if+it+no+longer+exists%2C+since+it+is+consumed+by+eternity.">Commentary on Wisdom 7:29</a></div>
          </div>
        </div>
      </div>

      <div class="tl-row" data-portrait="/portraits/remigius-of-auxerre.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">908</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/Remigius_of_Auxerre" target="_blank" rel="noopener">Remigius of Auxerre</a></h2>
          <div class="tl-quote">
            <p class="tl-text">For by the net of holy preaching they drew fish, that is, men, from the depths of the sea, that is, of infidelity, to the light of faith. Wonderful indeed is this fishing! for fishes when they are caught, soon after die; when men are caught by the word of preaching, they rather are made alive.</p>
            <div class="tl-cite">on Mark 1:17</div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">It was divinely provided that this title should be set up over His head, that the Jews might learn that not even by putting Him to death could they avoid having Him for their King; for in the very instrument of His death He not only did not lose, but rather confirmed His sovereignty.</p>
            <div class="tl-cite">on Matthew 27:37</div>
          </div>
        </div>
      </div>

      <div class="tl-row" data-portrait="/portraits/theophylact-of-ohrid.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">1107</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/Theophylact_of_Ohrid" target="_blank" rel="noopener">Theophylact of Ohrid</a></h2>
          <div class="tl-quote">
            <p class="tl-text">In appearance He was a man, but His deeds showed that He was God.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Theophylact%2520of%2520Ohrid%2FCommentary%2520on%2520Matthew.html#In+appearance+He+was+a+man%2C+but+His+deeds+showed+that+He+was+God.">Commentary on Matthew (on Matthew 8:26-27)</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">Furthermore, drunkenness gives the power to speak in different languages — drunkenness, which deprives one even of one's native tongue!</p>
            <div class="tl-cite"><a href="/by_father.php?file=Theophylact%2520of%2520Ohrid%2FCommentary%2520on%2520Acts%2FChapter%25202.html#Furthermore%2C+drunkenness+gives+the+power+to+speak+in+different+languages+%E2%80%94+drunkenness%2C+which+deprives+one+even+of+one%27s+native+tongue%21">Commentary on Acts (on Acts 2:13)</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">God takes me as the pattern He will follow: what I do to another, He does to me.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Theophylact%2520of%2520Ohrid%2FCommentary%2520on%2520Matthew.html#God+takes+me+as+the+pattern+He+will+follow%3A+what+I+do+to+another%2C+He+does+to+me.">Commentary on Matthew (on Matthew 6:12)</a></div>
          </div>
        </div>
      </div>

      <div class="tl-row" data-portrait="/portraits/bernard-of-clairvaux.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">1153</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/Bernard_of_Clairvaux" target="_blank" rel="noopener">Bernard of Clairvaux</a></h2>
          <div class="tl-quote">
            <p class="tl-text">The man who is wise, therefore, will see his life as more like a reservoir than a canal. The canal simultaneously pours out what it receives; the reservoir retains the water till it is filled, then discharges the overflow without loss to itself... Today there are many in the Church who act like canals, the reservoirs are far too rare.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Bernard%2520of%2520Clairvaux%2FSermons%2520on%2520the%2520Song%2520of%2520Songs%2FSermon%252018.html#The+man+who+is+wise%2C+therefore%2C+will+see+his+life+as+more+like+a+reservoir+than+a+canal.+The+canal+simultaneously+pours+out+what+it+receives%3B+the+reservoir+retains+the+water+till+it+is+filled%2C+then+discharges+the+overflow+without+loss+to+itself...+Today+there+are+many+in+the+Church+who+act+like+canals%2C+the+reservoirs+are+far+too+rare.">Sermons on the Song of Songs, Sermon 18</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">For just as if you were passing through a doorway whose lintel, to speak so as to be understood, were too low, it would not harm you however much you stooped; but it would harm you if you raised yourself even a finger's breadth more than the measure of the door allows, so that you would strike and be bruised with your head battered; so in the soul there is plainly no humiliation however great to be feared, but rather the slightest self-exaltation rashly presumed is to be dreaded and exceedingly feared.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Bernard%2520of%2520Clairvaux%2FSermons%2520on%2520the%2520Song%2520of%2520Songs%2FSermon%252037.html#For+just+as+if+you+were+passing+through+a+doorway+whose+lintel%2C+to+speak+so+as+to+be+understood%2C+were+too+low%2C+it+would+not+harm+you+however+much+you+stooped%3B+but+it+would+harm+you+if+you+raised+yourself+even+a+finger%27s+breadth+more+than+the+measure+of+the+door+allows%2C+so+that+you+would+strike+and+be+bruised+with+your+head+battered%3B+so+in+the+soul+there+is+plainly+no+humiliation+however+great+to+be+feared%2C+but+rather+the+slightest+self-exaltation+rashly+presumed+is+to+be+dreaded+and+exceedingly+feared.">Sermons on the Song of Songs, Sermon 37</a></div>
          </div>
        </div>
      </div>

      <div class="tl-row" data-portrait="/portraits/bonaventure.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">1257</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/Bonaventure" target="_blank" rel="noopener">Bonaventure</a></h2>
          <div class="tl-quote">
            <p class="tl-text">The Lord illuminated this city by his presence, by which he condescended to sinners, by which he also drew sinners to himself. For compassion draws the wretched, as a magnet draws iron.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Bonaventure%2FCommentary%2520on%2520Luke%2FChapter%252019.html#The+Lord+illuminated+this+city+by+his+presence%2C+by+which+he+condescended+to+sinners%2C+by+which+he+also+drew+sinners+to+himself.+For+compassion+draws+the+wretched%2C+as+a+magnet+draws+iron.">Commentary on Luke, Chapter 19</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">He who does not fear God must fear everywhere; and he who truly fears God has what no one can take from him. But he who fears something other than God has what ought to be taken from him. He who fears God cannot lose God. It is not so with money. If a man has money, he fears lest he lose it, and yet he is certain that he will lose it. But he who fears God is secure everywhere.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Bonaventure%2FCollationes%2520de%2520Septem%2520Donis%2FCollation%25202.html#He+who+does+not+fear+God+must+fear+everywhere%3B+and+he+who+truly+fears+God+has+what+no+one+can+take+from+him.+But+he+who+fears+something+other+than+God+has+what+ought+to+be+taken+from+him.+He+who+fears+God+cannot+lose+God.+It+is+not+so+with+money.+If+a+man+has+money%2C+he+fears+lest+he+lose+it%2C+and+yet+he+is+certain+that+he+will+lose+it.+But+he+who+fears+God+is+secure+everywhere.">Collationes de Septem Donis, Collation 2</a></div>
          </div>
        </div>
      </div>

      <div class="tl-row" data-portrait="/portraits/thomas-aquinas.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">1274</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/Thomas_Aquinas" target="_blank" rel="noopener">Thomas Aquinas</a></h2>
          <div class="tl-quote">
            <p class="tl-text">For in whatever thing a man establishes his ultimate end, that thing is his god.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Thomas%2520Aquinas%2FCommentary%2520on%2520Matthew.htm#For+in+whatever+thing+a+man+establishes+his+ultimate+end%2C+that+thing+is+his+god">Commentary on Matthew 6:24</a></div>
          </div>
          <div class="tl-quote">
            <p class="tl-text">We also must go and start out by making progress: because he who stands still runs the risk of being unable to preserve the life of grace. For, along the road to God, if we do not go forward we fall back.</p>
            <div class="tl-cite"><a href="/by_father.php?file=Thomas%2520Aquinas%2FCommentary%2520on%2520John%2F4.htm#We+also+must+go+and+start+out+by+making+progress%3A+because+he+who+stands+still+runs+the+risk+of+being+unable+to+preserve+the+life+of+grace.+For%2C+along+the+road+to+God%2C+if+we+do+not+go+forward+we+fall+back.">Commentary on John 4:50</a></div>
          </div>
        </div>
      </div>

      <div class="tl-era">
        <div class="tl-era-line"></div>
        <div class="tl-era-label">The Reformation and after</div>
        <div class="tl-era-line"></div>
      </div>

      <div class="tl-row" data-portrait="/portraits/martin-luther.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">1546</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/Martin_Luther" target="_blank" rel="noopener">Martin Luther</a></h2>
          <div class="tl-quote">
            <p class="tl-text">What did the Fathers do except seek and present the clear and open testimonies of Scripture?</p>
            <div class="tl-cite">Against Latomus</div>
          </div>
        </div>
      </div>

      <div class="tl-row" data-portrait="/portraits/john-wesley.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">1791</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name"><a href="https://en.wikipedia.org/wiki/John_Wesley" target="_blank" rel="noopener">John Wesley</a></h2>
          <div class="tl-quote">
            <p class="tl-text">Can any who spend several years in those seats of learning, be excused, if they do not add to that of the languages and sciences, the knowledge of the Fathers &mdash; the most authentic commentators on Scripture, as being both nearest the fountain, and eminently endued with that Spirit by whom all Scripture was given. It will be easily perceived, I speak chiefly of those who wrote before the Council of Nice. But who would not likewise desire to have some acquaintance with those that followed them &mdash; with St. Chrysostom, Basil, Jerome, Austin; and, above all, the man of a broken heart, Ephraim Syrus...</p>
            <div class="tl-cite">An Address to the Clergy</div>
          </div>
        </div>
      </div>

      <div class="tl-row" data-portrait="/portraits/cs-lewis.jpg">
        <div class="tl-yearcol">
          <div class="tl-year">1963</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name">C. S. Lewis</h2>
          <div class="tl-quote">
            <p class="tl-text">Every age has its own outlook. It is specially good at seeing certain truths and specially liable to make certain mistakes. We all, therefore, need the books that will correct the characteristic mistakes of our own period. And that means the old books.</p>
            <p class="tl-text">All contemporary writers share to some extent the contemporary outlook &mdash; even those, like myself, who seem most opposed to it. Nothing strikes me more when I read the controversies of past ages than the fact that both sides were usually assuming without question a good deal which we should now absolutely deny.</p>
            <p class="tl-text">They thought that they were as completely opposed as two sides could be, but in fact they were all the time secretly united &mdash; united with each other and against earlier and later ages &mdash; by a great mass of common assumptions.</p>
            <p class="tl-text">We may be sure that the characteristic blindness of the twentieth century &mdash; the blindness about which posterity will ask, &lsquo;But how could they have thought that?&rsquo; &mdash; lies where we have never suspected it, and concerns something about which there is untroubled agreement between Hitler and President Roosevelt or between Mr. H. G. Wells and Karl Barth.</p>
            <p class="tl-text">None of us can fully escape this blindness, but we shall certainly increase it, and weaken our guard against it, if we read only modern books. Where they are true they will give us truths which we half knew already. Where they are false they will aggravate the error with which we are already dangerously ill.</p>
            <p class="tl-text">The only palliative is to keep the clean sea breeze of the centuries blowing through our minds, and this can be done only by reading old books. Not, of course, that there is any magic about the past. People were no cleverer then than they are now; they made as many mistakes as we. But not the same mistakes.</p>
            <p class="tl-text">They will not flatter us in the errors we are already committing; and their own errors, being now open and palpable, will not endanger us. Two heads are better than one, not because either is infallible, but because they are unlikely to go wrong in the same direction.</p>
            <p class="tl-text">To be sure, the books of the future would be just as good a corrective as the books of the past, but unfortunately we cannot get at them.</p>
            <div class="tl-cite"><a href="https://web.archive.org/web/20070222105839/https://www.spurgeon.org/~phil/history/ath-inc.htm" target="_blank" rel="noopener" style="color: var(--gold);">On the Reading of Old Books</a></div>
          </div>
        </div>
      </div>

      <div class="tl-row">
        <div class="tl-yearcol">
          <div class="tl-year">Now</div>
        </div>
        <div class="tl-spine">
          <div class="tl-dot" style="background: var(--gold); border-color: var(--gold);"></div>
        </div>
        <div class="tl-body">
          <h2 class="tl-name">You</h2>
          <div class="tl-quote">
            <p class="tl-text">&ldquo;When a learned man is presented with any statement in an ancient author, the one question he never asks is whether it is true. To regard the ancient writer as a possible source of knowledge &mdash; to anticipate that what he said could possibly modify your thoughts or your behaviour &mdash; this would be rejected as unutterably simple-minded.&rdquo;</p>
            <div class="tl-cite">Screwtape counted on it &mdash; C. S. Lewis, <em>The Screwtape Letters</em>, Letter XXVII</div>
          </div>
        </div>
      </div>
        </div>

        <section class="close">
            <p class="quote">Praise, therefore, be to him who grants us to begin and makes us to finish. Amen.</p>
            <p class="by">&mdash; Thomas Aquinas&rsquo; <a href="/by_father.php?file=Thomas%2520Aquinas%2FCommentary%2520on%2520Isaiah.html#Praise%2C+therefore%2C+be+to+him+who+grants+us+to+begin+and+makes+us+to+finish.+Amen.">Commentary on Isaiah</a></p>
        </section>

    </main>

    <footer class="footer">
        <div class="wrap" style="text-align:center;">
            <details class="credits">
                <summary>Image credits</summary>
                <p>Two portrait medallions are used under Creative Commons Attribution-ShareAlike, and are offered under the same license:</p>
                <ul>
                    <li><strong>Caesarius of Arles</strong> &mdash; statue of St C&eacute;saire, Arles; photo by Neoclassicism Enthusiast via <a href="https://commons.wikimedia.org/wiki/File:Arles,St_C%C3%A9saire27,choeur7,St_C%C3%A9saire_(cropped).jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, <a href="https://creativecommons.org/licenses/by-sa/4.0/" target="_blank" rel="noopener">CC BY-SA 4.0</a>.</li>
                    <li><strong>Andreas of Caesarea</strong> &mdash; miniature from a 17th-c. Tolkovy Apocalypse (Russian State Library, f.37 no.408) via <a href="https://commons.wikimedia.org/wiki/File:St.Andriy_Kesarijskiy.jpg" target="_blank" rel="noopener">Wikimedia Commons</a>, <a href="https://creativecommons.org/licenses/by-sa/3.0/" target="_blank" rel="noopener">CC BY-SA 3.0</a>.</li>
                </ul>
            </details>
        </div>
    </footer>

    <script>
    // The Gathering Host: build a medallion for each witness, then accumulate
    // coins in a fixed muster band as their rows scroll past the header.
    // Portraits drop in later by adding data-portrait="/path.jpg" to a .tl-row.
    (function () {
      var timeline = document.querySelector('.timeline');
      if (!timeline) return;

      var STOP = { of: 1, the: 1, de: 1, di: 1, 'in': 1, and: 1 };
      function sigilOf(name) {
        var words = name.split(/\s+/).filter(function (w) { return w && !STOP[w.toLowerCase()]; });
        if (!words.length) return name.slice(0, 2);
        if (words.length === 1) return words[0].slice(0, 2);
        return words[0][0] + words[words.length - 1][0];
      }
      // Build the fixed muster band.
      var host = document.createElement('div');
      host.className = 'host';
      host.setAttribute('aria-hidden', 'true');
      var rail = document.createElement('div');
      rail.className = 'host-rail';
      host.appendChild(rail);
      document.body.appendChild(host);

      // Enhance each witness row with a medallion; skip the "You / Now" node.
      var rows = [].slice.call(timeline.querySelectorAll('.tl-row'));
      var witnesses = [];
      rows.forEach(function (row) {
        var nameEl = row.querySelector('.tl-name');
        var yearEl = row.querySelector('.tl-year');
        if (!nameEl) return;
        var name = nameEl.textContent.trim();
        var year = yearEl ? yearEl.textContent.trim() : '';
        if (name.toLowerCase() === 'you' || year.toLowerCase() === 'now') return;

        var sig = sigilOf(name);
        var portrait = row.getAttribute('data-portrait');

        var head = document.createElement('div');
        head.className = 'tl-head';
        var med = document.createElement('span');
        med.className = 'medallion' + (portrait ? ' has-img' : '');
        med.innerHTML = '<span class="medallion-sigil">' + sig + '</span>';
        if (portrait) {
          var mimg = document.createElement('img');
          mimg.className = 'medallion-img'; mimg.src = portrait; mimg.alt = '';
          med.appendChild(mimg);
        }
        var body = nameEl.parentNode;
        body.insertBefore(head, nameEl);
        head.appendChild(med);
        head.appendChild(nameEl);

        witnesses.push({ row: row, sig: sig, portrait: portrait, coin: null });
      });
      if (!witnesses.length) return;

      // Compress the rank as it fills, the way a real host closes ranks: each
      // coin advances by the full 26px while there's room, then the coins
      // overlap more and more as the host grows, so the rank never spills past
      // the muster band no matter how narrow the screen.
      var COIN = 26;
      function relayout() {
        var coins = rail.children;
        var n = coins.length;
        if (!n) return;
        // Base the track on the viewport (the band spans it), within the rail's
        // max-width and its 16px side padding. Measuring the rail itself won't
        // work: it shrink-wraps the coins, so width and layout chase each other.
        var avail = Math.min(window.innerWidth, 900) - 32;
        var contrib = n > 1 ? Math.min(COIN, (avail - COIN) / (n - 1)) : COIN;
        if (contrib < 1) contrib = 1;  // a sliver, even when the host is huge
        for (var i = 0; i < n; i++) {
          coins[i].style.marginLeft = i === 0 ? '0px' : (contrib - COIN) + 'px';
        }
      }

      function addCoin(w) {
        if (w.coin) return;
        var coin = document.createElement('span');
        coin.className = 'coin enter' + (w.portrait ? ' has-img' : '');
        coin.innerHTML = '<span class="coin-sigil">' + w.sig + '</span>';
        if (w.portrait) {
          var cimg = document.createElement('img');
          cimg.className = 'coin-img'; cimg.src = w.portrait; cimg.alt = '';
          coin.appendChild(cimg);
        }
        // Keep the rank in chronological order.
        var idx = witnesses.indexOf(w), next = null;
        for (var j = idx + 1; j < witnesses.length; j++) {
          if (witnesses[j].coin) { next = witnesses[j].coin; break; }
        }
        rail.insertBefore(coin, next);
        w.coin = coin;
        relayout();
        requestAnimationFrame(function () {
          requestAnimationFrame(function () { coin.classList.remove('enter'); });
        });
      }
      function removeCoin(w) {
        if (!w.coin) return;
        if (w.coin.parentNode) w.coin.parentNode.removeChild(w.coin);
        w.coin = null;
        relayout();
      }

      function updateActive() {
        var any = false;
        for (var i = 0; i < witnesses.length; i++) if (witnesses[i].coin) { any = true; break; }
        host.classList.toggle('active', any);
      }

      var ticking = false;
      function sync() {
        ticking = false;
        var line = host.getBoundingClientRect().bottom;
        for (var i = 0; i < witnesses.length; i++) {
          var w = witnesses[i];
          if (w.row.getBoundingClientRect().top < line) addCoin(w); else removeCoin(w);
        }
        updateActive();
      }
      function onScroll() { if (!ticking) { ticking = true; requestAnimationFrame(sync); } }
      window.addEventListener('scroll', onScroll, { passive: true });
      window.addEventListener('resize', function () { relayout(); onScroll(); }, { passive: true });

      sync();
    })();
    </script>

    <script>
    // ============================================================
    // EASTER EGG — "The army had faces."
    // Click the gold divider-lozenge to toggle <body class="fire">. A few
    // rows swap their reverent quote for a spicier one; a holy-white flame band
    // burns along the foot of the page and a tongue of fire rests on each
    // medallion (CSS). Everything is additive and reversible — the default
    // markup is stored and restored, never rewritten.
    // ============================================================
    (function () {
      var timeline = document.querySelector('.timeline');
      var mark = document.querySelector('.divider-mark');
      if (!timeline || !mark) return;

      // SPICY_NAMES lists which timeline rows take part in the swap. The quotes
      // themselves are fetched on demand from spicy.php the first time the egg is
      // lit — so the landing page never ships or parses any of this data.
      var SPICY_NAMES = [
        'Ignatius of Antioch', 'Origen of Alexandria', 'Tertullian', 'Jerome',
        'Augustine of Hippo', 'Epiphanius of Salamis', 'Cosmas Indicopleustes',
        'Justin Martyr', 'Irenaeus', 'Clement of Alexandria', 'Hippolytus of Rome',
        'Cyprian', 'Methodius of Olympus', 'Lactantius', 'Eusebius of Caesarea',
        'Hilary of Poitiers', 'Ephrem the Syrian', 'Athanasius of Alexandria',
        'Basil of Caesarea', 'Ambrosiaster', 'Cyril of Jerusalem', 'Gregory of Nazianzus',
        'Gregory of Nyssa', 'Ambrose of Milan', 'Didymus the Blind', 'John Chrysostom',
        'Theodore of Mopsuestia', 'John Cassian', 'Cyril of Alexandria', 'Theodoret of Cyrus',
        'Leo the Great', 'Desert Fathers', 'Philoxenus of Mabbug', 'Cassiodorus',
        'John Damascene', 'Alcuin of York', 'Rabanus Maurus', 'Theophylact of Ohrid',
        'Bernard of Clairvaux', 'Bonaventure', 'Thomas Aquinas'
      ];
      var nameSet = {};
      SPICY_NAMES.forEach(function (n) { nameSet[n] = true; });

      // Quotes are fetched once, then cached; the swap awaits this promise.
      var SPICY_QUOTES = null, spicyPromise = null;
      function loadSpicy() {
        if (SPICY_QUOTES) return Promise.resolve(SPICY_QUOTES);
        if (spicyPromise) return spicyPromise;
        spicyPromise = fetch('/spicy.php')
          .then(function (r) { return r.json(); })
          .then(function (data) { SPICY_QUOTES = data; return data; });
        return spicyPromise;
      }

      // Index the participating rows now (by name only — no quote data needed),
      // stashing a restore-point on each for the swap.
      var rows = [].slice.call(timeline.querySelectorAll('.tl-row'));
      var targets = [];   // { row, body, name, injected:node }
      rows.forEach(function (row) {
        var nameEl = row.querySelector('.tl-name');
        if (!nameEl) return;
        var name = nameEl.textContent.trim();
        if (!nameSet[name]) return;
        targets.push({ row: row, body: row.querySelector('.tl-body'), name: name, injected: null });
      });

      function buildInjection(quotes) {
        var wrap = document.createElement('div');
        wrap.className = 'spicy-inject';
        wrap.innerHTML = quotes.map(function (q) {
          return '<div class="tl-quote"><p class="tl-text">' + q.text + '</p>' +
                 '<div class="tl-cite">' + q.cite + '</div></div>';
        }).join('');
        return wrap;
      }

      // Holy-white flame band along the foot of the page — an original,
      // noise-driven WebGL fire shader. three.js is loaded lazily on first
      // light, so the page carries no WebGL cost until the egg is triggered;
      // the render loop runs only while lit. If three.js can't load, the CSS
      // .band-glow remains as a quiet warm fallback.
      var band = document.createElement('div');
      band.id = 'fire-band';
      band.setAttribute('aria-hidden', 'true');
      band.innerHTML = '<div class="band-glow"></div>';
      var canvas = document.createElement('canvas');
      band.appendChild(canvas);
      document.body.appendChild(band);

      var reduceMotion = false;
      try { reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches; } catch (e) {}

      // Vertically-stretched, upward-scrolling simplex noise gated by a
      // height-rising threshold => tapering flame tongues. snoise is the
      // public-domain 2D simplex (Gustavson / patriciogonzalezvivo gist).
      var VERT = 'varying vec2 vUv; void main(){ vUv = uv; gl_Position = vec4(position, 1.0); }';
      var FRAG = [
        'precision highp float;',
        'varying vec2 vUv;',
        'uniform float u_time; uniform vec2 u_res; uniform float u_intensity;',
        'vec3 permute(vec3 x){return mod(((x*34.0)+1.0)*x,289.0);}',
        'float snoise(vec2 v){',
        '  const vec4 C=vec4(0.211324865405187,0.366025403784439,-0.577350269189626,0.024390243902439);',
        '  vec2 i=floor(v+dot(v,C.yy)); vec2 x0=v-i+dot(i,C.xx);',
        '  vec2 i1=(x0.x>x0.y)?vec2(1.0,0.0):vec2(0.0,1.0);',
        '  vec4 x12=x0.xyxy+C.xxzz; x12.xy-=i1; i=mod(i,289.0);',
        '  vec3 p=permute(permute(i.y+vec3(0.0,i1.y,1.0))+i.x+vec3(0.0,i1.x,1.0));',
        '  vec3 m=max(0.5-vec3(dot(x0,x0),dot(x12.xy,x12.xy),dot(x12.zw,x12.zw)),0.0);',
        '  m=m*m; m=m*m; vec3 x=2.0*fract(p*C.www)-1.0; vec3 h=abs(x)-0.5;',
        '  vec3 ox=floor(x+0.5); vec3 a0=x-ox;',
        '  m*=1.79284291400159-0.85373472095314*(a0*a0+h*h);',
        '  vec3 g; g.x=a0.x*x0.x+h.x*x0.y; g.yz=a0.yz*x12.xz+h.yz*x12.yw;',
        '  return 130.0*dot(m,g);',
        '}',
        'void main(){',
        '  vec2 uv=vUv; float aspect=u_res.x/u_res.y; float t=u_time;',
        '  float S=7.0;',
        '  float n  = snoise(vec2(uv.x*aspect*S,        uv.y*0.22*S - t*1.15));',
        '  n       += 0.45*snoise(vec2(uv.x*aspect*S*2.0 + t*0.5, uv.y*0.34*S - t*1.9));',
        '  n       += 0.20*snoise(vec2(uv.x*aspect*S*4.0,          uv.y*0.5*S  - t*2.7));',
        '  n = 0.5 + 0.42*n;',
        '  float shape = pow(uv.y, 0.62);',
        '  float fuel  = n * u_intensity;',
        '  float top   = smoothstep(1.05, 0.60, uv.y);',
        '  float edge  = fuel - shape;',                      // signed distance past the flame front
        '  float alpha = smoothstep(-0.02, 0.12, edge) * top;',
        '  float heat  = smoothstep(-0.08, 0.42, edge);',     // smooth across the tongue width
        '  float lowHot = smoothstep(0.95, 0.08, uv.y);',     // white-hot low, cooling to gold at the tips
        '  float h = heat * mix(0.45, 1.0, lowHot);',
        '  vec3 gold = vec3(1.0, 0.64, 0.20);',               // gold fringe / tips
        '  vec3 warm = vec3(1.0, 0.89, 0.62);',               // warm white body
        '  vec3 col  = mix(gold, warm, smoothstep(0.0, 0.5, h));',
        '  col       = mix(col, vec3(1.0), smoothstep(0.6, 1.0, h));',  // white-hot cores
        '  gl_FragColor = vec4(col, alpha);',
        '}'
      ].join('\n');

      var fire = { started: false, loading: false, renderer: null, scene: null,
                   camera: null, material: null, clock: null, raf: 0, onResize: null };

      function fireLoop() {
        if (!fire.started || !lit || reduceMotion) { fire.raf = 0; return; }
        fire.material.uniforms.u_time.value = fire.clock.getElapsedTime();
        fire.renderer.render(fire.scene, fire.camera);
        fire.raf = requestAnimationFrame(fireLoop);
      }

      function startFire() {
        if (!lit) return;
        if (fire.started) { if (!reduceMotion && !fire.raf) fireLoop(); return; }
        if (fire.loading) return;
        fire.loading = true;
        import('https://esm.sh/three@0.160.0').then(function (THREE) {
          fire.loading = false;
          if (!lit) return;   // doused before three.js finished loading
          var renderer = new THREE.WebGLRenderer({ alpha: true, canvas: canvas, antialias: true, premultipliedAlpha: false });
          renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 2));
          var scene = new THREE.Scene();
          var camera = new THREE.OrthographicCamera(-0.5, 0.5, 0.5, -0.5, 0.1, 10);
          var material = new THREE.ShaderMaterial({
            uniforms: { u_time: { value: 0 }, u_res: { value: new THREE.Vector2(1, 1) }, u_intensity: { value: 1.0 } },
            vertexShader: VERT, fragmentShader: FRAG, transparent: true
          });
          scene.add(new THREE.Mesh(new THREE.PlaneGeometry(2, 2), material));
          fire.renderer = renderer; fire.scene = scene; fire.camera = camera;
          fire.material = material; fire.clock = new THREE.Clock(); fire.started = true;
          fire.onResize = function () {
            var w = band.clientWidth, h = band.clientHeight;
            renderer.setSize(w, h, false);
            material.uniforms.u_res.value.set(w, h);
          };
          window.addEventListener('resize', fire.onResize, { passive: true });
          fire.onResize();
          if (reduceMotion) { material.uniforms.u_time.value = 6.0; renderer.render(scene, camera); }
          else { fireLoop(); }
        }).catch(function () { fire.loading = false; });   // CSS glow stays as fallback
      }

      function stopFire() {
        if (fire.raf) { cancelAnimationFrame(fire.raf); fire.raf = 0; }
      }

      // Extinguish control.
      var exit = document.createElement('button');
      exit.type = 'button';
      exit.className = 'fire-exit';
      exit.innerHTML = 'Extinguish 🔥';
      document.body.appendChild(exit);

      // Guido Reni's St Michael — lazy-loaded the first time fire is lit, then
      // faded in. body.fire handles the show/hide; this only fetches the image.
      var michaelImg = document.querySelector('.michael-img');
      function ensureMichael() {
        if (!michaelImg || michaelImg.getAttribute('src')) return;
        michaelImg.addEventListener('load', function () { michaelImg.classList.add('ready'); });
        michaelImg.src = '/images/st-michael.jpg';
      }

      var lit = false;
      function light() {
        if (lit) return; lit = true;
        document.body.classList.add('fire');
        mark.setAttribute('aria-pressed', 'true');
        // Curate the timeline and start the visuals immediately — none of this
        // needs the quote data...
        filter();
        resyncHost();
        startFire();
        ensureMichael();
        // ...then swap in the spicier quotes once they've been fetched. The
        // participating rows keep their reverent quotes until this resolves.
        loadSpicy().then(function (data) {
          if (!lit) return;   // doused before the quotes arrived
          targets.forEach(function (t) {
            if (t.injected) return;
            var quotes = data[t.name] || [];
            if (!quotes.length) return;
            var inj = buildInjection(quotes);
            var firstQuote = t.body.querySelector('.tl-quote');
            t.body.insertBefore(inj, firstQuote);
            t.injected = inj;
            // Hide, don't destroy, the reverent originals (skip the ones we injected).
            [].forEach.call(t.body.querySelectorAll('.tl-quote'), function (q) {
              if (!inj.contains(q)) q.style.display = 'none';
            });
          });
          resyncHost();
        }).catch(function () {});   // fetch failed: the fire still shows, quotes just don't swap
      }

      // Curate: keep only the swapped fathers; hide the rest, and any era
      // divider left with nothing under it.
      function filter() {
        var keep = targets.map(function (t) { return t.row; });
        var kids = [].slice.call(timeline.children);
        kids.forEach(function (el) {
          if (el.classList.contains('tl-row') && keep.indexOf(el) === -1) el.classList.add('fire-hidden');
        });
        for (var i = 0; i < kids.length; i++) {
          if (!kids[i].classList.contains('tl-era')) continue;
          var hasVisible = false;
          for (var j = i + 1; j < kids.length; j++) {
            if (kids[j].classList.contains('tl-era')) break;
            if (kids[j].classList.contains('tl-row') && !kids[j].classList.contains('fire-hidden')) { hasVisible = true; break; }
          }
          if (!hasVisible) kids[i].classList.add('fire-hidden');
        }
      }

      // The host band syncs on scroll; nudge it so its coins match the current
      // (curated or full) set of visible rows.
      function resyncHost() {
        try { window.dispatchEvent(new Event('scroll')); } catch (e) {}
      }
      function douse() {
        if (!lit) return; lit = false;
        document.body.classList.remove('fire');
        mark.setAttribute('aria-pressed', 'false');
        targets.forEach(function (t) {
          if (t.injected) { t.injected.remove(); t.injected = null; }
          [].forEach.call(t.body.querySelectorAll('.tl-quote'), function (q) { q.style.display = ''; });
        });
        [].forEach.call(timeline.querySelectorAll('.fire-hidden'), function (el) { el.classList.remove('fire-hidden'); });
        resyncHost();
        stopFire();
      }
      function toggle() { lit ? douse() : light(); }

      // The lozenge is the trigger — quiet, but reachable by keyboard too.
      mark.setAttribute('role', 'button');
      mark.setAttribute('tabindex', '0');
      mark.setAttribute('aria-label', 'A hidden ember');
      mark.setAttribute('aria-pressed', 'false');
      mark.addEventListener('click', toggle);
      mark.addEventListener('keydown', function (ev) {
        if (ev.key === 'Enter' || ev.key === ' ') { ev.preventDefault(); toggle(); }
      });
      exit.addEventListener('click', douse);
      document.addEventListener('keydown', function (ev) { if (ev.key === 'Escape' && lit) douse(); });
    })();
    </script>
</body>
</html>
