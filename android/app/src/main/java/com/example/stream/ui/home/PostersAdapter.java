package com.example.stream.ui.home;

import android.content.Context;
import android.content.Intent;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.example.stream.R;
import com.example.stream.data.model.ContentItem;
import com.example.stream.player.PlayerActivity;
import com.example.stream.ui.home.WatchLaterHelper;

import java.util.ArrayList;
import java.util.List;

public class PostersAdapter extends RecyclerView.Adapter<PostersAdapter.VH> {
    private final List<ContentItem> data;

    public PostersAdapter(List<ContentItem> data) {
        this.data = data;
    }

    public void submit(List<ContentItem> newData) {
        data.clear();
        data.addAll(newData);
        notifyDataSetChanged();
    }

    @NonNull
    @Override
    public VH onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View v = LayoutInflater.from(parent.getContext()).inflate(R.layout.item_poster, parent, false);
        return new VH(v);
    }

    @Override
    public void onBindViewHolder(@NonNull VH holder, int position) {
        ContentItem item = data.get(position);
        holder.title.setText(item.title);
        // Simple: no image loader dependency; use placeholder
        holder.image.setImageResource(android.R.drawable.ic_media_play);
        holder.itemView.setOnClickListener(v -> {
            Context ctx = v.getContext();
            if (item.video_url != null && !item.video_url.isEmpty()) {
                Intent i = new Intent(ctx, PlayerActivity.class);
                i.putExtra("title", item.title);
                i.putExtra("url", item.video_url);
                ctx.startActivity(i);
            }
        });

        holder.itemView.setOnLongClickListener(v -> {
            Context ctx = v.getContext();
            new WatchLaterHelper(ctx).add(item.id);
            Toast.makeText(ctx, "Added to Watch Later", Toast.LENGTH_SHORT).show();
            return true;
        });
    }

    @Override
    public int getItemCount() { return data.size(); }

    static class VH extends RecyclerView.ViewHolder {
        ImageView image; TextView title;
        public VH(@NonNull View itemView) {
            super(itemView);
            image = itemView.findViewById(R.id.image);
            title = itemView.findViewById(R.id.title);
        }
    }
}
