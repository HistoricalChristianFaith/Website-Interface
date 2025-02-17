This is the reference implementation/demo of the sqlite file [compiled](https://github.com/HistoricalChristianFaith/Commentaries-Database/blob/master/compile_data.py) from our [Commentaries-Database](https://github.com/HistoricalChristianFaith/Commentaries-Database), served up with a user-interface at https://historicalchristian.faith/

It also contains a frontend interface to our [Writings-Database](https://github.com/HistoricalChristianFaith/Writings-Database).

Any changes made in master branch on this repo will reflect <a href='https://historicalchristian.faith/' target='_blank'>on the website</a> within a couple minutes.

# Build/Deploy Process

1) [Compile](https://github.com/HistoricalChristianFaith/Commentaries-Database/blob/master/compile_data.py) a SQL file from the [Commentaries-Database](https://github.com/HistoricalChristianFaith/Commentaries-Database). [Or just download the [latest SQL file release](https://github.com/HistoricalChristianFaith/Commentaries-Database/releases/tag/latest)]
2) Move the resulting `data.sqlite` file to this `Website-Interface` directory.
4) Now serve the files via a PHP webserver, and it should just work.

# Alternatives

- https://catenabible.com
    - The most polished app, and a wonderful bible companion!
    - It's a closed database, which contains data from a wide variety of sources (not just the ANF/NPNF series)
    - Negatives:
        - It identifies the person behind a quote, but not the work in which the quote appears.
        - Its commentaries are tied only to individual verses (and not passages that span multiple verses) 
        - Its commentaries from the ANF/NPNF often are lacking context / are cut off.

- https://www.earlychristianwritings.com/e-catena/
    - A wonderful quick reference
    - Only includes citations from the ANF/NPNF
    - But does identify the work in which a quote appears, AND provides a link directly to that work!
    
- https://www.catholiccrossreference.online/fathers/
    - Similar to earlychristianwritings.com/e-catena
    - Uses citations from the ANF/NPNF
    - Identifies the work in which a quote appears, AND provides a link directly to that work!

- https://www.biblindex.org/
    - The most... scholarly?
    - Laborious to use
    - Requires a bigger brain than I have.

- [Ancient Christian Commentary on Scripture](https://www.logos.com/product/31152/ancient-christian-commentary-on-scripture-complete-set-accs)
    - Contains commentaries from a wide variety of sources, many of which appear to be custom translated just for this product!
    - Identifies the work in which a quote appears, and often provides good historical background!
    - The commentaries shown are not exhaustive, but are curated... usually with just a couple chosen per verse.
    - Costs enough to empty your wallet.
