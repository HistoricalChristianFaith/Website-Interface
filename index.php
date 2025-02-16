<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Historical Christian Commentaries and Writings</title>
    <meta name="description" content="Historical Christian Commentary for the Bible / Writings in the Public Domain">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,700;1,400;1,700&family=Open+Sans:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }
        h1, h2, h3, .navbar-brand {
            font-family: 'Lora', serif;
        }
        .navbar {
            background-color: #1a237e !important;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        .navbar-brand {
            color: #ffffff !important;
            font-weight: 700;
            font-size: 1.5rem;
        }
        .hero {
            background: linear-gradient(rgba(26, 35, 126, 0.7), rgba(26, 35, 126, 0.7)), url('/api/placeholder/1200/600') no-repeat center center;
            background-size: cover;
            color: white;
            padding: 6rem 0;
            margin-bottom: 3rem;
        }
        .btn-custom-primary {
            background-color: #c62828;
            border-color: #c62828;
            color: white;
            transition: all 0.3s ease;
        }
        .btn-custom-primary:hover {
            background-color: #b71c1c;
            border-color: #b71c1c;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .btn-custom-secondary {
            background-color: #2e7d32;
            border-color: #2e7d32;
            color: white;
            transition: all 0.3s ease;
        }
        .btn-custom-secondary:hover {
            background-color: #1b5e20;
            border-color: #1b5e20;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .card {
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #1a237e;
            color: white;
            font-weight: 600;
        }
        .quote {
            font-style: italic;
            color: #555;
            font-family: 'Lora', serif;
        }
        .feature-icon {
            font-size: 2rem;
            color: #1a237e;
            margin-bottom: 1rem;
        }
        .footer {
            background-color: #1a237e;
            color: white;
            padding: 2rem 0;
            margin-top: 3rem;
        }
        .footer a {
            color: #bbdefb;
        }
        .dropdown-item {
            cursor: pointer;
        }
        .language-selector {
            color: white !important;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/">HistoricalChristian.Faith</a>
            <div class="ms-auto">
                <div class="dropdown">
                    <button class="btn dropdown-toggle language-selector" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-language"></i> Language
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
                        <li><a class="dropdown-item" onclick="changeLanguage('en')">English</a></li>
                        <li><a class="dropdown-item" onclick="changeLanguage('es')">Español</a></li>
                        <li><a class="dropdown-item" onclick="changeLanguage('pt')">Português</a></li>
                        <li><a class="dropdown-item" onclick="changeLanguage('fr')">Français</a></li>
                        <li><a class="dropdown-item" onclick="changeLanguage('it')">Italiano</a></li>
                        <li><a class="dropdown-item" onclick="changeLanguage('de')">Deutsch</a></li>
                        <li><a class="dropdown-item" onclick="changeLanguage('ru')">Русский</a></li>
                        <li><a class="dropdown-item" onclick="changeLanguage('pl')">Polski</a></li>
                        <li><a class="dropdown-item" onclick="changeLanguage('el')">Ελληνικά</a></li>
                        <li><a class="dropdown-item" onclick="changeLanguage('tl')">Filipino</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="hero text-center">
        <div class="container">
            <h1 class="display-4 mb-4">Explore Ancient Christian Wisdom</h1>
            <p class="lead mb-5">Discover how the early Christians, separated from Christ by mere generations, interpreted the same Bible we read today.</p>
            <div class="row justify-content-center">
                <div class="col-md-5 mb-3">
                    <a href="/matthew/1/all" class="btn btn-custom-secondary btn-lg w-100 py-3">
                        <i class="fas fa-book-open me-2"></i>View Bible With Historical Commentaries
                    </a>
                </div>
                <div class="col-md-5 mb-3">
                    <a href="/by_father.php" class="btn btn-custom-primary btn-lg w-100 py-3">
                        <i class="fas fa-scroll me-2"></i>Explore Historical Christian Writings
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card my-5">
            <div class="card-header">
                Why should you care how the early Christian Church interpreted the scriptures?
            </div>
            <div class="card-body">

            <p class="quote">Every age has its own outlook. It is specially good at seeing certain truths and specially liable to make certain mistakes. We all, therefore, need the books that will correct the characteristic mistakes of our own period. And that means the old books.</p>
            <p class="quote">All contemporary writers share to some extent the contemporary outlook—even those, like myself, who seem most opposed to it. Nothing strikes me more when I read the controversies of past ages than the fact that both sides were usually assuming without question a good deal which we should now absolutely deny.</p>
            <p class="quote">They thought that they were as completely opposed as two sides could be, but in fact they were all the time secretly united—united with each other and against earlier and later ages—by a great mass of common assumptions.</p>
            <p class="quote">We may be sure that the characteristic blindness of the twentieth century—the blindness about which posterity will ask, ‘But how could they have thought that?’—lies where we have never suspected it, and concerns something about which there is untroubled agreement between Hitler and President Roosevelt or between Mr. H. G. Wells and Karl Barth.</p>
            <p class="quote">None of us can fully escape this blindness, but we shall certainly increase it, and weaken our guard against it, if we read only modern books. Where they are true they will give us truths which we half knew already. Where they are false they will aggravate the error with which we are already dangerously ill.</p>
            <p class="quote">The only palliative is to keep the clean sea breeze of the centuries blowing through our minds, and this can be done only by reading old books. Not, of course, that there is any magic about the past. People were no cleverer then than they are now; they made as many mistakes as we. But not the same mistakes.</p>
            <p class="quote">They will not flatter us in the errors we are already committing; and their own errors, being now open and palpable, will not endanger us. Two heads are better than one, not because either is infallible, but because they are unlikely to go wrong in the same direction.</p>
            <p class="quote">To be sure, the books of the future would be just as good a corrective as the books of the past, but unfortunately we cannot get at them.</p>
            <p class="text-end">- <strong><a href='https://web.archive.org/web/20070222105839/https://www.spurgeon.org/~phil/history/ath-inc.htm' target='_blank'>C. S. Lewis</a></strong></p>
            <hr>

            <p class="quote">Can any who spend several years in those seats of learning, be excused, if they do not add to that of the languages and sciences, the knowledge of the Fathers - the most authentic commentators on Scripture, as being both nearest the fountain, and eminently endued with that Spirit by whom all Scripture was given. It will be easily perceived, I speak chiefly of those who wrote before the Council of Nice. But who would not likewise desire to have some acquaintance with those that followed them with St. Chrysostom, Basil, Jerome, Austin; and, above all, the man of a broken heart, Ephraim Syrus...</p>

            <p class="text-end">- <strong><a href='http://wesley.nnu.edu/john-wesley/an-address-to-the-clergy/' target='_blank'>John Wesley</a></strong></p>
            <hr>

            <p class="quote">What did the Fathers do except seek and present the clear and open testimonies of Scripture?</p>

            <p class="text-end">- <strong><a href='https://www.academia.edu/49575464/Excerpts_and_Comments_on_Luthers_Against_Latomus_Academia_' target='_blank'>Martin Luther</a></strong></p>
            <hr>

            <p class="quote">I have gone into this matter at some length... to show the difficulty in Holy Scripture. Men who altogether lack experience lay special claim to understanding it apart from the grace of God and the scholarship of preceding generations.</p>

            <p class="text-end">- <strong><a href='https://www.ccel.org/ccel/pearse/morefathers/files/jerome_daniel_02_text.htm' target='_blank'>Jerome</a></strong></p>
            <hr>

            <p class="quote">I have thought it my duty to quote all these passages from the writings of both Latin and Greek authors who, being in the Catholic Church before our time, have written commentaries on the divine oracles, in order that our brother, if he hold any different opinion from theirs, may know that it becomes him, laying aside all bitterness of controversy, and preserving or reviving fully the gentleness of brotherly love, to investigate with diligent and calm consideration either what he must learn from others, or what others must learn from him. For the reasonings of any men whatsoever, even though they be Catholics, and of high reputation, are not to be treated by us in the same way as the canonical Scriptures are treated. We are at liberty, without doing any violence to the respect which these men deserve, to condemn and reject anything in their writings, if perchance we shall find that they have entertained opinions differing from that which others or we ourselves have, by the divine help, discovered to be the truth. I deal thus with the writings of others, and I wish my intelligent readers to deal thus with mine.</p>

            <p class="text-end">- <strong><a href='https://www.newadvent.org/fathers/1102148.htm' target='_blank'>Augustine</a></strong></p>

            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container text-center">
            <p>
                Powered by the open source, crowd-sourced 
                <a href="https://github.com/HistoricalChristianFaith/Commentaries-Database" target='_blank'>HistoricalChristianFaith/Commentaries-Database</a> & 
                <a href="https://github.com/HistoricalChristianFaith/Writings-Database" target='_blank'>HistoricalChristianFaith/Writings-Database</a>
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Hide language dropdown if page is loaded via Google Translate
        if (window.location.hostname.includes("translate.goog")) {
            document.querySelector('.dropdown').style.display = 'none';
        }

        function changeLanguage(lang) {
            if (lang === 'en') {
                window.location.href = 'https://historicalchristian.faith/';
            } else {
                const baseUrl = 'https://historicalchristian-faith.translate.goog/';
                const params = `?_x_tr_sl=en&_x_tr_tl=${lang}&_x_tr_pto=wapp`;
                window.location.href = baseUrl + params;
            }
        }
    </script>
</body>
</html>