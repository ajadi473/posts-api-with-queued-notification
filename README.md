## Requirements:


- Laravel 8.0
- Laravel Sanctum.
- Posts identifiers have UUID instead of Big Integer.
- Images uploaded are stored in the local disk.
- Notifications are queued.

## Details

- Show posts in the feed with their information (Image, description, date, author) including total likes and the last 5 usernames who liked the post.
- Feed is public (Doesn’t need authentication), paginated, and order by creation date.
- Users should be authenticated to create or like/unlike posts.
- Users can remove their posts, with the image file.
- Users can like/unlike other posts.
- Users can see all likes of a specific post.
- Send a notification to other users when a new post is added. (Database channel)
- Automatically delete posts 15 days old.

## Heroku url

[Deploy URL](https://posts-api-with-queued-notify.herokuapp.com/).
