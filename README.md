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

## URL

[Deploy URL](https://posts-api-with-queued-notify.herokuapp.com/).

[![Run in Postman](https://run.pstmn.io/button.svg)](https://app.getpostman.com/run-collection/16275050-b7066c58-7635-41b9-ab11-20999abbe541?action=collection%2Ffork&collection-url=entityId%3D16275050-b7066c58-7635-41b9-ab11-20999abbe541%26entityType%3Dcollection%26workspaceId%3D678f30c1-c2fe-4f76-b0e4-16bae9f27b68)
