This is the reference implementation/demo of the sqlite file [compiled](https://github.com/HistoricalChristianFaith/Commentaries-Database/blob/master/compile_data.py) from our [Commentaries-Database](https://github.com/HistoricalChristianFaith/Commentaries-Database), served up with a user-interface at https://historicalchristian.faith/

It also contains a frontend interface to our [Writings-Database](https://github.com/HistoricalChristianFaith/Writings-Database).

Any changes made in master branch on this repo will reflect <a href='https://historicalchristian.faith/' target='_blank'>on the website</a> within a couple minutes.

# Build/Deploy Process

1) [Compile](https://github.com/HistoricalChristianFaith/Commentaries-Database/blob/master/compile_data.py) a SQL file from the [Commentaries-Database](https://github.com/HistoricalChristianFaith/Commentaries-Database). 
2) Move the resulting `data.sqlite` file to the `data/` directory here. 
3) Run data/kjv_sqlite.py to populate the `data.sqlite` with the King James Bible so that the relevant Bible verses for a user's query will show (KJB chosen because in the public domain)
4) Now serve the files via a PHP webserver, and it should just work.

# Why build this?

In an <a href='https://youtu.be/6T7pUEZfgdI?t=4009'>interview with Joe Rogan</a>, Jordan Peterson said the following:

<blockquote>
"I was looking through these sayings, these maxims, and that was one of them: The meek shall inherit the earth. I've been using this site called Biblehub and it's very interesting, it's organized very interesting, so you have a biblical line and then they have like three pages of commentary on each line, because people have commented on every verse in the Bible to the degree that's almost unimaginable, so you can look and see all the interpretations and all the translations and get some sense and get some sense of what the genuine meaning might be."
</blockquote>

The principle is valid, but the commentaries on Biblehub are mostly post-Reformation, within the last 500 years. It seems to me that in determining the genuine meaning of a passage of scripture, commentaries from the first 1500 years of Christianity hold a lot of value.

# People already build that, it's catenabible.com

If you pull up Matthew 23:35 [on catenabible.com](https://catenabible.com/mt/23/35) (which is a fantastic app btw), you'll see only a few commentaries. That is because catenabible.com associates each commentary with a SINGLE bible verse.

But what if a church father gives a commentary on the passage Matthew 23:32-36? In catenabible.com, you will only see those commentaries if you lookup Matthew 23:36. 

We believe that's an insufficient solution. If you lookup commentaries for Matthew 23:35, [you should see commentaries that overlap that verse at all](https://historicalchristian.faith/commentaries.php?search_query=Matthew%2023:35), including those on Matthew 23:32-36.

So in this repo we demonstrate a simple solution to the problem:

We convert chapter+verse identifiers into a single number, then use those numbers to establish a range in our SQL where clause:

```
$location_start = ($parsed_input['start']['chapter']*1000000) + $parsed_input['start']['verse'];
$location_end = ($parsed_input['end']['chapter']*1000000) + $parsed_input['end']['verse'];

$statement = $db->prepare("SELECT * FROM commentary WHERE book=(:book) and location_end >= (:location_start) and location_start <= (:location_end) ORDER BY ts ASC, location_start ASC");
```

Elegant? Not particularly. Works? Yup!

In addition to that, catenabible.com's database is closed. When I see an error in a commentary there, what do I do? When I come across a new obscure commentary on a hotly debated passage not currently in their database, what do I do? Thus the open database nature of this project.

By the way, I'm not trying to dog on catenabible.com here. I love their application - I hope that eventually they contribute to and utilize our open database.